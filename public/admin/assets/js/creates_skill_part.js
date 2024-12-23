document.addEventListener('DOMContentLoaded', function() {
    $('#skillPartForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Success:', response);
                alert('Data saved successfully!');
            },
            error: function(xhr) {
                if (xhr.status === 422) { // Validation error
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = "Please correct the following errors:\n";
                    Object.keys(errors).forEach(function(key) {
                        errorMessage += `${key}: ${errors[key].join(", ")}\n`;
                    });
                    alert(errorMessage);
                } else {
                    console.error('Error:', xhr.responseText);
                    alert('An error occurred. Please try again.');
                }
            }
        });
    });
    
    const skillSelect = document.getElementById('skillSelect');
    const partSelect = document.getElementById('partSelect');
    const formContainer = document.getElementById('formContainer');

    const parts = {
        Listening: ["Part 1", "Part 2", "Part 3"],
        Reading: ["Part 1", "Part 2", "Part 3", "Part 4"],
        Writing: ["Part 1", "Part 2"],
        Speaking: ["Part 1", "Part 2", "Part 3"]
    };

    const formTemplates = {
        Reading: {
            "Part 1": { questions: 10, start: 1 },
            "Part 2": { questions: 10, start: 11 },
            "Part 3": { questions: 10, start: 21 },
            "Part 4": { questions: 10, start: 31 }
        },
        Listening: {
            "Part 1": { questions: 8, start: 1 },
            "Part 2": { questions: 12, start: 9 },
            "Part 3": { questions: 15, start: 21 }
        },
        Writing: {
            "Part 1": { questions: 1, start: 1, textarea: true },
            "Part 2": { questions: 1, start: 2, textarea: true }
        },
        Speaking: {
            "Part 1": { questions: 2, start: 1, options: 4 },
            "Part 2": { textarea: true },
            "Part 3": { questions: 1, start: 3, file: true, options: 3 }
        }
    };

    skillSelect.addEventListener('change', function() {
        const selectedSkill = skillSelect.value;
        const partOptions = parts[selectedSkill] || [];
        
        partSelect.innerHTML = '<option selected>Choose part name</option>'; // Clear existing options
        partOptions.forEach(part => {
            const option = document.createElement('option');
            option.value = part.replace(" ", "_");
            option.textContent = part;
            partSelect.appendChild(option);
        });
    });

    partSelect.addEventListener('change', function() {
        const selectedSkill = skillSelect.value;
        const selectedPart = partSelect.value.replace("_", " ");
        formContainer.innerHTML = ''; // Clear previous form content

        if (selectedSkill && formTemplates[selectedSkill] && formTemplates[selectedSkill][selectedPart]) {
            const template = formTemplates[selectedSkill][selectedPart];
            const formHtml = generateFormHtml(selectedSkill, selectedPart, template);
            formContainer.innerHTML = formHtml;

            initializeEditors();
        }
    });

    function generateFormHtml(skill, part, template) {
        let html = '';
        const chosenPart = part.replace(" ", "_");
        if (skill === 'Reading') {
            html += `<textarea class="form-control mb-3" name="readingText" id="editor-${chosenPart}" rows="8" placeholder="Enter text here"></textarea>`;
            for (let i = 0; i < template.questions; i++) {
                const questionNumber = template.start + i;
                html += `<div class="mb-3">
                            <label>Question ${questionNumber}</label>
                            <input type="text" name="questions[${questionNumber}][text]" class="form-control mb-1" placeholder="Question ${questionNumber}">
                            ${generateOptionsHtml(questionNumber)}
                        </div>`;
            }
        } else if (skill === 'Listening') {
            html += '<input type="file" name="listeningAudioFile" class="form-control mb-3">';
            for (let i = 0; i < template.questions; i++) {
                const questionNumber = template.start + i;
                html += `<div class="mb-3">
                            <label>Question ${questionNumber}</label>
                            <input type="text" name="questions[${questionNumber}][text]" class="form-control mb-1" placeholder="Question ${questionNumber}">
                            ${generateOptionsHtml(questionNumber)}
                        </div>`;
        }
        } else if (skill === 'Writing') {
            // Assuming the template specifies the need for input and textarea
            if (template.questions) {
                // Dynamically generate question text input fields based on the number of questions
                for (let i = 0; i < template.questions; i++) {
                    const questionNumber = template.start + i;
                    html += `<div class="mb-3">
                                <label>Question ${questionNumber}</label>
                                <input type="text" name="questions[${questionNumber}][text]" class="form-control mb-1" placeholder="Enter question text">
                            </div>`;
                }
            }
            if (template.textarea) {
                // Include a textarea for additional writing text
                html += `<textarea class="form-control mb-3" name="readingText" id="editor-${chosenPart}" rows="8" placeholder="Enter extended text here"></textarea>`;
            }
        }else if (skill === 'Speaking') {
            if (part === 'Part 1') {
                for (let i = 0; i < template.questions; i++) {
                    html += `<div class="mb-3">
                                <input type="text" class="form-control mb-1" placeholder="Enter text here">
                                ${generateTextInputOptionsHtml(template.options)}
                            </div>`;
                }
            } else if (part === 'Part 2') {
                html += `<textarea class="form-control mb-3" name="readingText" id="editor-${chosenPart}" rows="8" placeholder="Enter text here"></textarea>`;
            } else if (part === 'Part 3') {
                    html += '<input type="text" class="form-control mb-3" placeholder="Enter text here">';
                if (template.file) {
                    html += `<input type="file" name="speakingAudioFile" class="form-control mb-3">`;
                }
                html += generateTextInputOptionsHtml(template.options);
            }
        }
        
        return html;
    }

    function generateOptionsHtml(questionNumber) {
        let optionsHtml = '';
        for (let j = 1; j <= 4; j++) {
            optionsHtml += `<div class="form-check">
                                <input class="form-check-input" type="radio" name="questions[${questionNumber}][options][${j}][isCorrect]" id="question${questionNumber}option${j}">
                                <input type="text" name="questions[${questionNumber}][options][${j}][text]" class="form-control mb-1" placeholder="Option ${j}">
                            </div>`;
        }
        return optionsHtml;
    }

    function generateTextInputOptionsHtml(options) {
        let optionsHtml = '';
        for (let j = 1; j <= options; j++) {
            optionsHtml += `<input type="text" class="form-control mb-1" placeholder="Option ${j}">`;
        }
        return optionsHtml;
    }

    function initializeEditors() {
        const selectedPart = partSelect.value.replace(" ", "_");
        // Correct the usage of template literals below
        const textareas = document.querySelectorAll(`#editor-${selectedPart}`);
        textareas.forEach(textarea => {
            if (!textarea.hasAttribute('data-initialized')) {
                ClassicEditor
                    .create(textarea, {})
                    .then(editor => {
                        console.log(`Editor for ${textarea.id} was initialized`, editor);
                        // Ensure textarea's value is updated whenever the editor data changes
                        editor.model.document.on('change:data', () => {
                            editor.updateSourceElement();
                        });
                    })
                    .catch(error => {
                        console.error(`Error occurred in initializing editor for ${textarea.id}:`, error);
                    });
                textarea.setAttribute('data-initialized', 'true');
            }
        });
    }
});