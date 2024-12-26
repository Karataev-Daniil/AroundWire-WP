document.addEventListener("DOMContentLoaded", function() {
    var forms = document.querySelectorAll('form');

    forms.forEach(function(form) {
        if (form.classList.contains('search-form')) return;

        var inputFields = form.querySelectorAll('input');

        inputFields.forEach(function(inputField) {
            if (inputField.type === 'submit') return;

            if (inputField) {
                inputField.addEventListener('focus', function(e) {
                    if (e.relatedTarget === null) {
                        setTabFocusState(this);
                    }
                    setFocusState(this);
                });

                inputField.addEventListener('blur', function() {
                    setBlurState(this);
                });

                inputField.addEventListener('input', function() {
                    setInputState(this);
                    if (this.type === 'tel') {
                        formatPhoneNumber(this);
                    }
                });

                inputField.addEventListener('mouseenter', function() {
                    setHoverState(this);
                });

                inputField.addEventListener('mouseleave', function() {
                    setDefaultState(this);
                });

                inputField.addEventListener('mousedown', function() {
                    setActiveState(this);
                });

                inputField.addEventListener('mouseup', function() {
                    setDefaultState(this);
                });

                inputField.addEventListener("keydown", function(event) {
                    if (this.type === 'tel') {
                        handleInput(inputField, event);
                    }
                });
            }
        });

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            inputFields.forEach(function(inputField) {
                inputField.classList.remove('default', 'filled', 'hovered', 'active');
                inputField.classList.add('default');
                if (inputField.type !== 'submit') {
                    inputField.value = inputField.defaultValue;
                }
            });
        });
    });

    function formatPhoneNumber(inputField) {
        let value = inputField.value.replace(/\D/g, "");
    
        if (value.charAt(0) === '1') {
            value = value.substring(1);
        }
    
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
    
        let formatted = "+1";
    
        formatted += " (";
        formatted += value.substring(0, 3);
        if (value.length < 3) formatted += "___".substring(value.length);
    
        formatted += ") "; 
    
        formatted += value.substring(3, 6);
        if (value.length < 6) formatted += "___".substring(value.length - 3);
    
        formatted += "-";
    
        formatted += value.substring(6, 10);
        if (value.length < 10) formatted += "____".substring(value.length - 6);
    
        if (!formatted.trim() || formatted === "+1 (___) ___-____") {
            inputField.value = "";
            inputField.classList.remove('filled');
        } else {
            inputField.value = formatted;
        }
    
        setCursorPosition(inputField, value);
    }
    
    function setCursorPosition(inputField, value) {
        let cursorPos = value.length + 4;
    
        if (value.length >= 3) cursorPos += 2;
        if (value.length >= 6) cursorPos += 1;
    
        if (inputField.selectionStart || inputField.selectionStart === '0') {
            inputField.setSelectionRange(cursorPos, cursorPos);
        }
    }
    
    function handleInput(inputField, event) {
        let value = inputField.value;
        const cursorPos = inputField.selectionStart;
    
        const nonDeletableChars = ['-', ')', ' ', '(', '+'];
    
        if (event.key === "Backspace") {
            if (cursorPos > 0 && value[cursorPos - 1] === ')') {
                inputField.setSelectionRange(cursorPos - 2, cursorPos - 2);
            } else if (cursorPos > 0 && value[cursorPos - 1] === ' ') {
                inputField.setSelectionRange(cursorPos - 2, cursorPos - 2);
            } else if (cursorPos > 0 && nonDeletableChars.includes(value[cursorPos - 1])) {
                inputField.setSelectionRange(cursorPos - 1, cursorPos - 1);
            }
        }
    }    
    
    function setFocusState(inputField) {
        if (
            inputField.type === "text" ||  
            inputField.type === "email" || 
            inputField.type === "tel"      
        ) {
            if (inputField.value === inputField.defaultValue) {
                inputField.value = "";
            }
        }

        inputField.classList.remove('default');
        inputField.classList.remove('filled');
    }

    function setTabFocusState(inputField) {
        inputField.classList.add('active');
    }

    function setBlurState(inputField) {
        inputField.classList.remove('focused');
        if (inputField.value === "" || inputField.value === inputField.defaultValue) {
            inputField.value = inputField.defaultValue;
            inputField.classList.add('default');
            inputField.classList.remove('active');
        } else {
            inputField.classList.add('filled');
        }
    }

    function setInputState(inputField) {
        if (inputField.value !== "" && inputField.value !== inputField.defaultValue) {
            inputField.classList.add('filled');
        } else {
            inputField.classList.remove('filled');
        }
    }

    function setHoverState(inputField) {
        inputField.classList.add('hovered');
    }

    function setDefaultState(inputField) {
        if (!inputField.classList.contains('focused') && !inputField.classList.contains('filled')) {
            inputField.classList.add('default');
        }
        inputField.classList.remove('hovered');
    }

    function setActiveState(inputField) {
        inputField.classList.add('active');
    }
});