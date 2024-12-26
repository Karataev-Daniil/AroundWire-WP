document.addEventListener("DOMContentLoaded", function() {
    const maxLength = 140;

    const inputs = document.querySelectorAll('input[type="text"], input[type="password"], input[type="email"], input[type="number"], input[type="search"], input[type="tel"], input[type="url"]');

    inputs.forEach(function(input) {
        input.setAttribute('maxlength', maxLength);
    });
});

// document.addEventListener('DOMContentLoaded', function () {
//     // Find all <li> elements with the class noFollow
//     const listItems = document.querySelectorAll('li.nofollow');

//     listItems.forEach(item => {
//         // Find the <a> tag inside the <li>
//         const anchor = item.querySelector('a');

//         // Check if <a> exists
//         if (anchor) {
//             const title = anchor.textContent; // Get the text inside <a> as title
//             const url = anchor.getAttribute('href'); // Get the value of href

//             // Create a new form
//             const form = document.createElement('form');
//             form.method = 'POST'; // Keep method as POST

//             // Create a button
//             const button = document.createElement('button');
//             button.className = 'noLink link-button'; // Default classes
//             button.type = 'submit';
//             button.name = 'prgpattern';
//             button.textContent = title; // Use the text from <a> as the button's text
//             button.value = url; // Store the URL in the button's value

//             // Transfer classes from <a> to the button
//             const anchorClasses = anchor.className; // Get classes from <a>
//             if (anchorClasses) {
//                 button.className += ' ' + anchorClasses; // Append the anchor classes to the button
//             }

//             // Add the button to the form
//             form.appendChild(button);

//             // Clear the current contents of <li> and add the new form
//             item.innerHTML = ''; // Clear the current contents
//             item.appendChild(form); // Add the new form
//         }
//     });

//     // Find all <a> elements with the class nofollow
//     const anchors = document.querySelectorAll('a.nofollow');

//     anchors.forEach(anchor => {
//         // Get the text and href from <a>
//         const title = anchor.textContent; // Get the text inside <a> as title
//         const url = anchor.getAttribute('href'); // Get the value of href

//         // Create a new form
//         const form = document.createElement('form');
//         form.method = 'POST'; // Keep method as POST

//         // Create a button
//         const button = document.createElement('button');
//         button.type = 'submit';
//         button.name = 'prgpattern';
//         button.textContent = title; // Use the text from <a> as the button's text
//         button.value = url; // Store the URL in the button's value

//         // Transfer classes from <a> to the button
//         const anchorClasses = anchor.className; // Get classes from <a>
//         if (anchorClasses) {
//             button.className = 'noLink link-button ' + anchorClasses; // Append the anchor classes to the button
//         } else {
//             button.className = 'noLink link-button'; // Default classes
//         }

//         // Add the button to the form
//         form.appendChild(button);

//         // Replace the <a> with the new form
//         anchor.parentElement.replaceChild(form, anchor); // Replace <a> with the form
//     });
// });
