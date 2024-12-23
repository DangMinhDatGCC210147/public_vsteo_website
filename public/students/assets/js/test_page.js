$(document).ready(function () {
    audioElements = $('audio');

    function formatTimeLimit(timeLimit) {
        if (timeLimit === '01:00:00') {
            return '60:00';
        }
        const parts = timeLimit.split(':');
        return parts[1] + ':' + parts[2];
    }

    function startCountdown(timeLimit) {
        clearInterval(countdownTimer);
        timeRemaining = timeLimit;

        countdownTimer = setInterval(function () {
            timeRemaining--;
            localStorage.setItem('timeRemaining', timeRemaining); // Lưu trạng thái thời gian vào localStorage
            if (timeRemaining <= 0) {
                clearInterval(countdownTimer);
                autoMoveToNextSkill();
            }
            updateTimerDisplay();
        }, 1000);
    }

    function convertTimeLimitToSeconds(timeLimit) {
        const parts = timeLimit.split(':');
        if (parts.length !== 3) {
            return 0; // Giá trị mặc định nếu định dạng không đúng
        }
        return parseInt(parts[0]) * 3600 + parseInt(parts[1]) * 60 + parseInt(parts[2]);
    }

    function updateTimerDisplay() {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        const timerElement = $('#skill-timer');
        // console.log(timerElement);
        $('#skill-timer').text(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);

        if (timeRemaining <= 300) {
            timerElement.addClass('flash-red');
        } else {
            timerElement.removeClass('flash-red');
        }
    }

    function showSkillPart(skillPart, skillId) {
        // Pause and reset all audio elements
        audioElements.each(function () {
            this.pause();
            this.currentTime = 0;
        });
        // Hide all content and question blocks
        $('.content-block, .question-block').hide();
        // Show the specified skill part
        $('[class*="' + skillPart + '"]').show();
        // Update button colors
        $('.skill-part-btn').removeClass('btn-warning').addClass('btn-secondary');
        $('.skill-part-btn[data-skill-part="' + skillPart + '"]').removeClass('btn-secondary')
            .addClass('btn-warning');
        // Save the current skill part to localStorage
        localStorage.setItem('currentSkillPart', skillPart);
        // Update the answered count display
        updateAnsweredCount(skillPart);
        // Scroll to the top of the container
        $('#content-area').scrollTop(0);
        $('.testForm').closest('.col-md-6').scrollTop(0);

    }

    function updateAnsweredCount(skillPart) {
        var answered = 0;
        var total = $('[class*="' + skillPart + '"].question-block').length;

        $('[class*="' + skillPart + '"].question-block textarea').each(function () {
            if ($(this).val().trim() !== '') {
                answered++;
            }
        });

        $('[class*="' + skillPart + '"].question-block').each(function () {
            if ($(this).find('input[type=radio]:checked').length > 0) {
                answered++;
            }
        });
        $('#answered-count span').text('Đã trả lời: ' + answered + '/' + total);
    }

    function updateSkillButtons() {
        $('.skill-part-btn').prop('disabled', true); // Disable all buttons
        $('.skill-part-btn[data-skill-id="' + skillIds[currentSkillIndex] + '"]').prop('disabled', false); // Enable buttons for current skill
        // Save the current skill index to localStorage
        localStorage.setItem('currentSkillIndex', currentSkillIndex);
    }

    function autoMoveToNextSkill() {
        Swal.fire({
            title: 'Hết thời gian',
            text: "Thời gian của kỹ năng này đã hết. Chuyển sang kỹ năng tiếp theo.",
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                currentSkillIndex++;
                if (currentSkillIndex >= skillIds.length) {
                    currentSkillIndex = skillIds.length - 1; // Ensure we don't go out of bounds
                }
                var formElement = $('#testForm-' + nextSkillId);
                if (formElement.length === 0) {
                    return;
                }
                var nextSkillName = formElement.attr('action');
                if (nextSkillName && nextSkillName.includes('Listening')) {
                    updateSkillButtons();
                    adjustLayoutForListening();
                    updateButtonToNextSkill();
                }
                if (nextSkillName && nextSkillName.includes('Reading')) {
                    updateSkillButtons();
                    adjustLayoutForReading();
                    updateButtonToNextSkill();
                }
                if (nextSkillName && nextSkillName.includes('Writing')) {
                    updateSkillButtons();
                    adjustLayoutForWriting();
                    updateButtonToNextSkill();
                }
                if (nextSkillName && nextSkillName.includes('Speaking')) {
                    waitingLayoutForSpeaking();
                }
            }
        });
    }

    function updateButtonToNextSkill() {
        var nextSkillId = skillIds[currentSkillIndex];
        var nextSkillPart = 'skill-' + nextSkillId + '-part-Part_1';
        var nextTimeLimit = $('[data-skill-id="' + nextSkillId + '"]').data('time-limit');
        currentSkillTimeLimit = convertTimeLimitToSeconds(nextTimeLimit);
        startCountdown(currentSkillTimeLimit);
        showSkillPart(nextSkillPart, nextSkillId);
    }

    // Get the saved skill part from localStorage or default to the initial skill part
    var savedSkillPart = localStorage.getItem('currentSkillPart') || skillPartIdentifier;
    var savedTimeLimit = localStorage.getItem('currentSkillPartTimeLimit') || '00:47:00'; // Thay đổi giá trị mặc định nếu cần thiết
    timeRemaining = parseInt(localStorage.getItem('timeRemaining')) || convertTimeLimitToSeconds(savedTimeLimit);

    // Initialize partAnswered object
    $('[class*="question-block"]').each(function () {
        var skillPart = $(this).attr('class').split(' ').find(cls => cls.startsWith('skill-'));
        partAnswered[skillPart] = partAnswered[skillPart] || {};
        var questionId = $(this).find('input[type=radio]').attr('name');
        partAnswered[skillPart][questionId] = false;
    });

    // Get the saved skill index from localStorage or default to 0
    currentSkillIndex = parseInt(localStorage.getItem('currentSkillIndex')) || 0;

    // Show the saved skill part or default
    var initialSkillId = skillIds[currentSkillIndex];
    currentSkillTimeLimit = timeRemaining; // Lưu thời gian giới hạn hiện tại
    startCountdown(timeRemaining); // Bắt đầu đếm ngược với thời gian còn lại hiện tại
    showSkillPart(savedSkillPart, initialSkillId);
    updateSkillButtons();

    $('.skill-part-btn').click(function () {
        var skillPart = $(this).data('skill-part');
        var skillId = $(this).data('skill-id');
        showSkillPart(skillPart, skillId);
    });

    $('#next-skill-btn').click(function () {
        Swal.fire({
            title: 'Xác nhận',
            text: "Bạn sẽ không thể quay lại kỹ năng trước đó. Bạn có chắc chắn muốn tiếp tục?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                currentSkillIndex++;
                if (currentSkillIndex >= skillIds.length) {
                    currentSkillIndex = skillIds.length - 1; // Ensure we don't go out of bounds
                }

                var nextSkillId = skillIds[currentSkillIndex];
                var formElement = $('#testForm-' + nextSkillId);
                if (formElement.length === 0) {
                    return;
                }
                var nextSkillName = formElement.attr('action');
                if (nextSkillName && nextSkillName.includes('Writing')) {
                    updateButtonToNextSkill()
                    updateSkillButtons();
                    adjustLayoutForWriting();
                }

                if (nextSkillName && nextSkillName.includes('Speaking')) {
                    waitingLayoutForSpeaking();
                }

                if (nextSkillName && nextSkillName.includes('Listening')) {
                    updateButtonToNextSkill()
                    updateSkillButtons();
                    adjustLayoutForListening();
                }
                if (nextSkillName && nextSkillName.includes('Reading')) {
                    updateButtonToNextSkill()
                    updateSkillButtons();
                    adjustLayoutForReading();
                }
            }
        });
    });
    
    function adjustLayoutForWriting() {
        var currentSkill = skillIds[currentSkillIndex];
        var currentSkillName = $('.skill-' + currentSkill + '-part-Part_1').closest('form').attr('action');

        if (currentSkillName.includes('Writing')) {
            $('#content-area, #form-area').removeClass('col-md-6').addClass('col-md-12'); // Expand to full width
            $('#content-area').css('height', '100%');
            $('#form-area').css('height', '26vw');
        }
    }

    function adjustLayoutForSpeaking() {
        var currentSkill = skillIds[currentSkillIndex];
        var currentSkillName = $('.skill-' + currentSkill + '-part-Part_1').closest('form').attr('action');

        if (currentSkillName.includes('Speaking')) {
            $('#content-area, #form-area').removeClass('col-md-12').addClass('col-md-6'); // Expand to full width
            $('#content-area').css('height', '100%');
            $('#form-area').css('height', '34vw');
        }
    }

    function adjustLayoutForListening() {
        var currentSkill = skillIds[currentSkillIndex];
        var currentSkillName = $('.skill-' + currentSkill + '-part-Part_1').closest('form').attr('action');

        if (currentSkillName.includes('Listening')) {
            $('#content-area, #form-area').removeClass('col-md-6').addClass('col-md-12'); // Expand to full width
            $('#content-area').css('height', '5vw');
        }
    }

    function adjustLayoutForReading() {
        var currentSkill = skillIds[currentSkillIndex];
        var currentSkillName = $('.skill-' + currentSkill + '-part-Part_1').closest('form').attr('action');

        if (currentSkillName.includes('Reading')) {
            $('#content-area, #form-area').removeClass('col-md-12').addClass('col-md-6'); // Expand to full width
            $('#content-area').css('height', '35vw');
            $('#form-area').css('height', '35vw');
        }
    }

    adjustLayoutForListening();
    adjustLayoutForWriting();
    adjustLayoutForReading();

    // Call the function when switching skills
    $('.skill-part-btn').click(function () {
        adjustLayoutForWriting();
        adjustLayoutForListening();
        adjustLayoutForReading();
    });

    $('#next-skill-btn').click(function () {
        adjustLayoutForWriting();
        adjustLayoutForListening();
        adjustLayoutForReading();
    });

    function waitingLayoutForSpeaking() {
        var currentSkill = skillIds[currentSkillIndex];
        var currentSkillName = $('.skill-' + currentSkill + '-part-Part_1').closest('form').attr('action');

        if (currentSkillName.includes('Speaking')) {
            $('#speakingPrepModal').modal('show');  // Show the modal
            let prepTime = 60;  // Set preparation time in seconds

            // Function to update the countdown timer
            function updateTimer() {
                if (prepTime > 0) {
                    prepTime--;
                    $('#prepTimer').text(prepTime);  // Update the display
                } else {
                    clearInterval(timer);  // Stop the timer when it reaches 0
                    $('#speakingPrepModal').modal('hide');  // Hide the modal
                }
            }

            let timer = setInterval(updateTimer, 1000);  // Set interval to update every second
            $('#speakingPrepModal').on('hidden.bs.modal', function () {
                updateSkillButtons();
                updateButtonToNextSkill();
                adjustLayoutForSpeaking();  
                showSpeakingSkillPart(speakingCurrentPartIndex);
                var currentSkill = getCurrentSkillName(); 
                toggleTimerVisibility(currentSkill);
            });
        }
    }

    // =================================================================================================
    // =================================================================================================
    // =================================================================================================
    // =================================================================================================
    
    let speakingCountdownTimer, speakingReadingTimer, speakingRecordingTimer;
    let speakingTimeRemaining, speakingCurrentPartIndex = 0;
    const speakingPartDetails = [
        { readingTime: 13, recordingTime: 180 }, // Part 1: 12s reading, 3 min recording
        { readingTime: 61, recordingTime: 180 }, // Part 2: 1 min reading, 3 min recording
        { readingTime: 61, recordingTime: 240 }  // Part 3: 1 min reading, 4 min recording
    ];

    function startSpeakingCountdown(duration, callback) {
        clearInterval(speakingCountdownTimer);
        speakingTimeRemaining = duration;
        speakingCountdownTimer = setInterval(() => {
            speakingTimeRemaining--;
            updateSpeakingTimerDisplay();
            if (speakingTimeRemaining <= 0) {
                clearInterval(speakingCountdownTimer);
                callback();
            }
        }, 1000);
    }

    function updateSpeakingTimerDisplay() {
        let minutes = Math.floor(speakingTimeRemaining / 60);
        let seconds = speakingTimeRemaining % 60;
        $('#speaking-skill-timer').text(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
    }

    function showSpeakingSkillPart(partIndex) {
        const partId = `part-${partIndex + 1}`;
        $('.speaking-question-block').hide(); // Hide all parts
        $(`.skill-${partId}`).show(); // Show current part
        prepareSpeakingPart(partIndex);
    }

    function prepareSpeakingPart(index) {
        if (index < speakingPartDetails.length) {
            const part = speakingPartDetails[index];
            console.log(`Reading time for part ${index + 1}: ${part.readingTime} seconds`);
            startSpeakingCountdown(part.readingTime, () => startSpeakingRecordingPart(index));
        }
    }

    function startSpeakingRecordingPart(index) {
        const part = speakingPartDetails[index];
        console.log(`Recording time for part ${index + 1}: ${part.recordingTime} seconds`);
        startSpeakingCountdown(part.recordingTime, () => endSpeakingRecordingPart(index));
        // Start recording
        speakingStartRecording(); // This function should handle the actual media recording
    }

    function endSpeakingRecordingPart(index) {
        speakingStopRecording(); // This function should handle stopping the media recording
        speakingCurrentPartIndex++;
        if (speakingCurrentPartIndex < speakingPartDetails.length) {
            showSpeakingSkillPart(speakingCurrentPartIndex);
        } else {
            console.log("All speaking parts completed.");
            // Optionally, auto-submit the form or enable a submit button
            $('#speaking-submitFormButton').prop('disabled', false);
        }
    }
    function updateButtonToNextSkillSpeaking() {
        var nextSkillId = skillIds[currentSkillIndex];
        var nextSkillPart = 'skill-' + nextSkillId + '-part-Part_1';
        showSkillPart(nextSkillPart, nextSkillId);
    }

    function speakingStartRecording() {
        console.log("Recording started...");
        $('#notification').show();
        // Handle actual media recording start
    }

    var currentPartIndex = 0; 
    // console.log(currentPartIndex);
    function speakingStopRecording() {
        console.log("Recording stopped.");
        $('#notification').hide();

        currentPartIndex++;
        var nextPartButton = $('.skill-part-btn[data-part-index="' + currentPartIndex + '"]');
        console.log(nextPartButton.length);
        if (nextPartButton.length > 0) {
            nextPartButton.click();  // Simulate a click on the next part button
        } else {
            console.log("No more parts to display.");
        }
    }

    // Optionally, handle form submission, etc.
    $('#speaking-submitFormButton').click(function() {
        console.log("Form submitted.");
    });

    function toggleTimerVisibility(skillType) {
        console.log("Toggle timer visibility: " + skillType);
        var skillTimer = document.getElementById('skill-timer');
        var speakingSkillTimer = document.getElementById('speaking-skill-timer');
        if (skillType == 'Speaking') {
            if (skillTimer) skillTimer.style.display = 'none';
            if (speakingSkillTimer) speakingSkillTimer.style.display = 'inline';
        } else {
            if (skillTimer) skillTimer.style.display = 'inline';
            if (speakingSkillTimer) speakingSkillTimer.style.display = 'none';
        }
    }
    
    function getCurrentSkillName() {
        var currentSkill = skillIds[currentSkillIndex];
        var currentSkillName = $('.skill-' + currentSkill + '-part-Part_1').closest('form').attr('action');
        if (currentSkillName.includes('Speaking')) {
            return "Speaking";
        }
    }
    // =================================================================================================
    // =================================================================================================
    // =================================================================================================
    // =================================================================================================
    
    // $('#submitTestButton').click(function () {
    //     $('#testForm').submit();
    // });

    $('input[type=radio]').change(function () {
        var skillPart = $(this).closest('.question-block').attr('class').split(' ').find(cls => cls.startsWith('skill-'));
        var questionId = $(this).attr('name');

        if (!partAnswered[skillPart][questionId]) {
            partAnswered[skillPart][questionId] = true;
            if (!answeredCount[skillPart]) {
                answeredCount[skillPart] = 0;
            }
            answeredCount[skillPart]++;
        }

        updateAnsweredCount(skillPart);
    });
    //Count the number of question answered
    $('textarea').on('input', function () {
        var skillPart = $(this).closest('.question-block').attr('class').split(' ').find(cls => cls.startsWith('skill-'));
        var questionId = $(this).attr('id'); // Assuming the id of the textarea serves as a unique question identifier

        // Check if there's any content in the textarea (considered answered if not empty)
        if ($(this).val().trim() !== '' && !partAnswered[skillPart][questionId]) {
            partAnswered[skillPart][questionId] = true; // Mark this question as answered
            if (!answeredCount[skillPart]) {
                answeredCount[skillPart] = 0;
            }
            answeredCount[skillPart]++; // Increment the count for this part
        } else if ($(this).val().trim() === '' && partAnswered[skillPart][questionId]) {
            partAnswered[skillPart][questionId] = false; // Mark as unanswered
            answeredCount[skillPart]--; // Decrement the count if text is removed
        }

        // Update the displayed count for this part
        updateAnsweredCount(skillPart);
    });

    $('#reset-btn').click(function () {
        // Clear localStorage
        localStorage.clear();
        // Reload the page to reset everything
        location.reload();
    });

    // Restore radio button selections
    $('input[type="radio"]').each(function () {
        var questionId = $(this).attr('name');
        var savedValue = localStorage.getItem(questionId);

        if (savedValue && $(this).val() === savedValue) {
            $(this).prop('checked', true);
        }
    });
    //Textarea
    // Restore textarea content from localStorage on page load
    $('textarea').each(function () {
        var questionId = $(this).attr('id');
        var savedContent = localStorage.getItem(questionId);
        if (savedContent !== null) {
            $(this).val(savedContent);
        }
    });

    // Save textarea content to localStorage on input change
    $('textarea').on('input', function () {
        var questionId = $(this).attr('id');
        var content = $(this).val();
        localStorage.setItem(questionId, content);
    });

    // Listen for changes on any radio button
    $('input[type="radio"]').change(function () {
        var questionId = $(this).attr('name');
        var selectedValue = $(this).val();

        // Save the selection to localStorage
        localStorage.setItem(questionId, selectedValue);
    });

    // Function to count words in a string
    function countWords(str) {
        return str.trim().split(/\s+/).filter(function (word) {
            return word.length > 0;
        }).length;
    }

    // Event listener for all textareas
    $('textarea').each(function () {
        var textareaId = $(this).attr('id');
        var wordCountId = 'wordCount_' + textareaId.split('_')[1];
        var savedContent = localStorage.getItem('content_' + textareaId);
        var savedWordCount = localStorage.getItem('wordCount_' + textareaId);

        if (savedContent !== null) {
            $(this).val(savedContent);
            $('#' + wordCountId).text(savedWordCount + ' words');
        }
    });

    // Event listener for all textareas
    $('textarea').on('input', function () {
        var textareaId = $(this).attr('id');
        var wordCountId = 'wordCount_' + textareaId.split('_')[1];
        var content = $(this).val();
        var words = countWords(content);

        // Update the word count display
        $('#' + wordCountId).text(words + ' words');
        // Save the content and word count to localStorage
        localStorage.setItem('content_' + textareaId, content);
        localStorage.setItem('wordCount_' + textareaId, words);
    });

});


$(document).ready(function () {
    $('#save-btn').click(function (e) {
        e.preventDefault(); // Prevent default form submission

        var totalForms = $('.testForm').length; // Total number of forms
        var completedForms = 0; // Counter for successfully submitted forms
        var popupShown = false; // Flag to check if popup is shown

        // Iterate through each form and send it via AJAX
        $('.testForm').each(function () {
            var form = $(this);
            var actionUrl = form.attr('action'); // Get action from form attribute

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(), // Serialize form data
                success: function (response) {
                    console.log('Data saved for form with action: ' + actionUrl);
                    completedForms++; // Increment counter on success
                    if ((completedForms === totalForms - 1) && !popupShown || completedForms == 1) { // Check if all forms are submitted and popup not shown
                        popupShown = true; // Set the flag to true
                        Swal.fire({ // Use Swal.fire() to create a SweetAlert2 popup
                            title: 'Success!',
                            text: 'All responses have been saved successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error saving data for form with action: ' + actionUrl);
                }
            });
        });
    });
});
