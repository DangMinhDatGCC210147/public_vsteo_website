$(document).ready(function () {

    var audioElements = $('audio');
    var countdownInterval;
    var preparationCountdownStarted = false;
    var skillTimers = {
        'Listening': 47 * 60,
        'Reading': 60 * 60,
        'Writing': 60 * 60,
        'Speaking': 12  // Initial 12 seconds for Speaking
    };
    var currentSkillName = null; // Track the current skill name
    var speakingPart = 0; // Track the current part of the Speaking skill

    // Set CSRF token for all Ajax requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function showPart(skillId, partId, shouldResetTimer = false) {
        audioElements.each(function () {
            this.pause();
            this.currentTime = 0;
        });
        var audioPlayer = document.getElementById('audioPlayer-' + skillId);
        if (audioPlayer) {
            // Kiểm tra trạng thái đã phát trong localStorage
            if (localStorage.getItem('played-' + skillId) === 'true') {
                audioPlayer.controls = false; // Vô hiệu hóa các điều khiển
            } else {
                // Lắng nghe sự kiện kết thúc để đảm bảo không phát lại và disable
                audioPlayer.addEventListener('ended', function () {
                    this.currentTime = 0;
                    this.pause();
                    this.controls = false; // Vô hiệu hóa các điều khiển
                    localStorage.setItem('played-' + skillId, 'true'); // Lưu trạng thái đã phát
                });
            }
        }

        $('.skill-content').hide();
        var partSelector = $('[data-skill-id="' + skillId + '"][data-part-id="' + partId + '"]');
        partSelector.show();

        $('.skill-part-btn').removeClass('btn-warning').addClass('btn-secondary');
        partSelector.removeClass('btn-secondary').addClass('btn-warning');

        var skillName = partSelector.closest('.skill-section').find('.skill-part-btn[data-skill-id="' + skillId + '"]').data('skill-name');
        if (currentSkillName !== skillName || shouldResetTimer) {
            startTimer(skillName, shouldResetTimer);
            currentSkillName = skillName;
            currentSkillId = skillId; // Set currentSkillId
        }

        adjustLayout(partSelector);

        // Kiểm tra nếu kỹ năng là "Speaking" và part là "Part_1"
        if (skillName === "Speaking" && partId === "Part_1") {
            // Vô hiệu hóa các nút part khác
            $('#notification-take-note').show();
            $('.skill-part-btn[data-skill-name="Speaking"]').prop('disabled', true);
            $('.skill-part-btn[data-skill-name="Speaking"][data-part-id="Part_1"]').prop('disabled', false);
        }

        // Save the current skillId and partId to localStorage
        localStorage.setItem('currentSkillId', skillId);
        localStorage.setItem('currentPartId', partId);
        localStorage.setItem('currentSkillName', skillName);

        // Enable current skill parts and disable others
        $('.skill-part-btn').each(function () {
            var btnSkillName = $(this).data('skill-name');
            if (btnSkillName === skillName && partId === "Part_1") {
                $(this).prop('disabled', false);
            } else if (btnSkillName === skillName && skillName !== "Speaking") {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });
    }

    function adjustLayout(partSelector) {
        var skillText = partSelector.closest('.skill-section').find('.skill-timer').text().trim();
        if (skillText.startsWith('Reading') || skillText.startsWith('Speaking')) {
            $('.content-area, .form-area').removeClass('col-md-12').addClass('col-md-6').css('height', '36vw');
        } else {
            $('.content-area, .form-area').removeClass('col-md-6').addClass('col-md-12').css('height', '100%');
        }

        if (skillText.startsWith('Listening')) {
            $('.form-area').css({ 'overflow': 'scroll', 'max-height': '33vw' });
        } else {
            $('.form-area').css({ 'overflow': 'hidden', 'max-height': '100%' });
        }

        $('#content-row, .content-area, .form-area').scrollTop(0);
    }

    function startTimer(skillName, shouldResetTimer = false) {
        clearInterval(countdownInterval);

        var duration;
        if (shouldResetTimer || !localStorage.getItem(`timer-${skillName}`)) {
            duration = skillTimers[skillName];
        } else {
            duration = parseInt(localStorage.getItem(`timer-${skillName}`), 10);
        }

        var display = $('#skill-timer');
        var timer = duration, minutes, seconds;
        countdownInterval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.text(minutes + ":" + seconds);
            localStorage.setItem(`timer-${skillName}`, timer);

            if (--timer < 0) {
                clearInterval(countdownInterval);
                display.text("00:00");
                localStorage.removeItem(`timer-${skillName}`);
                if (currentSkillName === "Writing") {
                    if (!preparationCountdownStarted) {
                        $('#speakingPrepModal').modal('show');
                        startPreparationCountdown();
                        preparationCountdownStarted = true;
                    }
                } else if (currentSkillName === "Speaking") {
                    handleSpeakingCountdownTransition();
                } else if (currentSkillName === "Listening" || currentSkillName === "Reading") {
                    Swal.fire({
                        html: `
                                <h3>Transitioning!</h3>
                                <h4>Moving to the next skill.</h4>
                            `,
                        icon: 'info',
                        timer: 2000,
                        timerProgressBar: true,
                        didClose: () => {
                            enableNextSkillButtons(currentSkillName);
                        }
                    });
                }
            }
        }, 1000);
    }

    function handleSpeakingCountdownTransition() {
        speakingPart++;
        $('#notification').hide();
        $('#notification-take-note').hide();
        const testId = $('input[name="test_id"]').val();
        if (speakingPart === 1) {
            const recordingControls = document.querySelector('.recording-controls[data-part-id="Part_1"]');
            skillTimers['Speaking'] = 60 * 3;
            $('#notification').show();
            $('#audioMotion').show();
            startRecording(60 * 3 + 1, recordingControls, speakingPart, testId); // Start recording for 3 minutes
        } else if (speakingPart === 2) {
            $('.skill-part-btn[data-skill-name="Speaking"]').prop('disabled', true);
            $('.skill-part-btn[data-skill-name="Speaking"][data-part-id="Part_2"]').prop('disabled', false);
            skillTimers['Speaking'] = 60;
            $('.skill-part-btn[data-skill-name="Speaking"][data-part-id="Part_2"]').click();
            $('#notification-take-note').show();
        } else if (speakingPart === 3) {
            const recordingControls = document.querySelector('.recording-controls[data-part-id="Part_2"]');
            skillTimers['Speaking'] = 60 * 3;
            $('#notification').show();
            $('#audioMotion').show();
            startRecording(60 * 3 + 1, recordingControls, speakingPart, testId); // Start recording for 3 minutes
        } else if (speakingPart === 4) {
            skillTimers['Speaking'] = 60;
            $('.skill-part-btn[data-skill-name="Speaking"]').prop('disabled', true);
            $('.skill-part-btn[data-skill-name="Speaking"][data-part-id="Part_3"]').prop('disabled', false);
            $('.skill-part-btn[data-skill-name="Speaking"][data-part-id="Part_3"]').click();
            $('#notification-take-note').show();
        } else if (speakingPart === 5) {
            const recordingControls = document.querySelector('.recording-controls[data-part-id="Part_3"]');
            skillTimers['Speaking'] = 60 * 4;
            $('#notification').show();
            $('#audioMotion').show();
            startRecording(60 * 4 + 1, recordingControls, speakingPart, testId); // Start recording for 4 minutes
        }

        if (speakingPart <= 5) {
            startTimer('Speaking');
        }
    }
    function saveEachForm(currentSkillName) {
        var formToSubmit = $('.testForm').filter(function() {
            return $(this).attr('action').includes(currentSkillName);
        }).first();

        if (formToSubmit.length) {
            // Lấy dữ liệu form
            var formData = formToSubmit.serialize();
            // Thực hiện AJAX request
            $.ajax({
                url: formToSubmit.attr('action'), // URL để gửi dữ liệu
                type: 'POST', // Phương thức gửi dữ liệu
                data: formData, // Dữ liệu form
                success: function(response) {
                    console.log('Form submitted successfully:', response);
                    // Xử lý khi gửi form thành công
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error submitting form:', textStatus, errorThrown);
                    // Xử lý khi có lỗi xảy ra
                }
            });
        }
    }

    function enableNextSkillButtons(currentSkillName) {
        saveEachForm(currentSkillName);
        var skillNames = $('.skill-part-btn').map(function () {
            return $(this).data('skill-name');
        }).get();
        var uniqueSkillNames = [...new Set(skillNames)];
        var nextSkillName = uniqueSkillNames[uniqueSkillNames.indexOf(currentSkillName) + 1];
        if (nextSkillName) {
            $('.skill-part-btn[data-skill-name="' + nextSkillName + '"]').prop('disabled', false);
            $('.skill-part-btn[data-skill-name="' + currentSkillName + '"]').prop('disabled', true);
            $('.skill-part-btn[data-skill-name="' + nextSkillName + '"]:first').click();
        }
    }

    function startPreparationCountdown() {
        var timer = 60;
        var interval = setInterval(function () {
            $('#prepTimer').text(--timer);
            if (timer <= 0) {
                clearInterval(interval);
                $('#speakingPrepModal').modal('hide');
                enableNextSkillButtons("Writing");
                initializeFunctions();
            }
        }, 1000);
    }

    function initializeFunctions() {
        // Kiểm tra xem có skillId và partId nào được lưu trong localStorage không
        var savedSkillId = localStorage.getItem('currentSkillId');
        var savedPartId = localStorage.getItem('currentPartId');
        var savedSkillName = localStorage.getItem('currentSkillName');

        if (savedSkillId && savedPartId) {
            showPart(savedSkillId, savedPartId, false);
        } else {
            var initialSkillPartBtn = $('.skill-part-btn').first();
            showPart(initialSkillPartBtn.data('skill-id'), initialSkillPartBtn.data('part-id'), true);
            savedSkillName = initialSkillPartBtn.data('skill-name');  // Set skill name for the first load
        }

        // Kích hoạt các nút part của skill hiện tại, vô hiệu hóa các skill khác
        $('.skill-part-btn').each(function () {
            var btnSkillName = $(this).data('skill-name');
            if (btnSkillName === savedSkillName) {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });

        // Vô hiệu hóa các phần của kỹ năng Speaking khi ở Part_1
        if (localStorage.getItem('currentSkillName') === "Speaking" && localStorage.getItem('currentPartId') === "Part_1") {
            $('.skill-part-btn[data-skill-name="Speaking"]').prop('disabled', true);
            $('.skill-part-btn[data-skill-name="Speaking"][data-part-id="Part_1"]').prop('disabled', false);
        }

        $('.skill-part-btn').on('click', function () {
            showPart($(this).data('skill-id'), $(this).data('part-id'), false);
        });

        updateAnsweredCount(savedSkillId, savedPartId);

        $('input[type="radio"]').on('change', function () {
            var skillContent = $(this).closest('.skill-content');
            updateAnsweredCount(skillContent.data('skill-id'), skillContent.data('part-id'));
        });

        $('.skill-part-btn').on('click', function () {
            updateAnsweredCount($(this).data('skill-id'), $(this).data('part-id'));
        });

        $('textarea').on('input', function () {
            var questionId = $(this).attr('id').split('_')[1];
            $(`#wordCount_${questionId}`).text(`${countWords($(this).val())} words`);
        });

        $('#save-btn').click(function (e) {
            e.preventDefault();
            saveForms();
        });

        $('#next-skill-btn').click(function () {
            handleNextSkillButtonClick();
        });

        $('input[type="radio"]').each(function () {
            restoreRadioSelection($(this));
        });

        $('textarea').each(function () {
            restoreTextareaContent($(this));
        });

        $('textarea').on('input', function () {
            saveTextareaContent($(this));
        });

        $('input[type="radio"]').change(function () {
            saveRadioSelection($(this));
        });
    }

    function updateAnsweredCount(skillId, partId) {
        var questions = $(`.skill-content[data-skill-id="${skillId}"][data-part-id="${partId}"] .options-container`);
        var answeredCount = questions.filter(function () {
            return $(this).find('input[type="radio"]:checked').length > 0;
        }).length;
        $('#answered-count').text(`Số câu đã hoàn thành: ${answeredCount}/${questions.length}`);
    }

    function countWords(text) {
        return text.trim().split(/\s+/).filter(word => word.length > 0).length;
    }

    function saveForms() {
        var totalForms = $('.testForm').length;
        var completedForms = 0;
        var popupShown = false;

        $('.testForm').each(function () {
            var form = $(this);
            $.post(form.attr('action'), form.serialize())
                .done(function () {
                    completedForms++;
                    if ((completedForms === totalForms - 1 && !popupShown) || completedForms === 1) {
                        popupShown = true;
                        Swal.fire({
                            html: `
                                <h3>Success!</h3>
                                <h4>All responses have been saved successfully.</h4>
                            `,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                })
                .fail(function () {
                    console.error('Error saving data for form with action: ' + form.attr('action'));
                });
        });
    }

    function handleNextSkillButtonClick() {
        var currentSkillName = $('.skill-part-btn:not(:disabled)').first().data('skill-name');
        var skillNames = $('.skill-part-btn').map(function () {
            return $(this).data('skill-name');
        }).get();
        var uniqueSkillNames = [...new Set(skillNames)];
        var nextSkillName = uniqueSkillNames[uniqueSkillNames.indexOf(currentSkillName) + 1];

        if (nextSkillName) {
            var confirmationText = null;

            if ((currentSkillName === "Listening" && nextSkillName === "Reading") || (currentSkillName === "Reading" && nextSkillName === "Writing")) {
                confirmationText = "You won't be able to go back to the previous skill!";
            } else if (currentSkillName === "Writing" && nextSkillName === "Speaking") {
                confirmationText = "You will have 60 seconds to prepare after you click OK.";
            }

            if (confirmationText) {
                Swal.fire({
                    html: `
                        <h3>Are you sure?</h3>
                        <h4>${confirmationText}</h4>
                    `,
                    title: '',
                    text: confirmationText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, continue!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (currentSkillName === "Writing" && nextSkillName === "Speaking") {
                            if (!preparationCountdownStarted) {
                                $('#speakingPrepModal').modal('show');
                                startPreparationCountdown();
                                preparationCountdownStarted = true;
                            }
                        } else {
                            enableNextSkillButtons(currentSkillName);
                        }
                    }
                });
            } else {
                enableNextSkillButtons(currentSkillName);
            }
        } else if (currentSkillName === "Speaking") {
            // Nếu currentSkillName là "Speaking" và không có kỹ năng tiếp theo
            Swal.fire({
                html: `
                    <h3>Final Skill</h3>
                    <h4>This is the final skill of the test.</h4>
                `,
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }
    }

    function restoreRadioSelection(radioButton) {
        var questionId = radioButton.attr('name');
        var savedValue = localStorage.getItem(questionId);

        if (savedValue && radioButton.val() === savedValue) {
            radioButton.prop('checked', true);
        }
    }

    function restoreTextareaContent(textarea) {
        var questionId = textarea.attr('id');
        var savedContent = localStorage.getItem(questionId);
        if (savedContent !== null) {
            textarea.val(savedContent);
        }
    }

    function saveTextareaContent(textarea) {
        var questionId = textarea.attr('id');
        var content = textarea.val();
        localStorage.setItem(questionId, content);
    }

    function saveRadioSelection(radioButton) {
        var questionId = radioButton.attr('name');
        var selectedValue = radioButton.val();
        localStorage.setItem(questionId, selectedValue);
    }

    initializeFunctions();

    $('#reset-btn').click(function () {
        localStorage.clear();
        location.reload();
    });

    setTimeout(function() {
        localStorage.clear();
        location.reload();
    }, 5 * 3600 * 1000); // 3 giờ x 3600 giây/giờ x 1000 mili giây/giây

    // window.onbeforeunload = function() {
    //     return "Bạn có chắc chắn muốn tải lại trang? Mọi thay đổi chưa được lưu có thể sẽ mất.";
    // };
});
