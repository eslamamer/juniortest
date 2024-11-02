document.addEventListener('DOMContentLoaded', function() {
    const errorContainer = document.querySelector('.error-messages');
    const switcher = document.getElementById('productType');
    const types = document.querySelectorAll('.types');
    let typeErrors = [];
    let formErrors = [];
    let validationErrors = [];

    // Hide all type-specific input sections
    function toggle() {
        types.forEach(element => {
            element.style.display = 'none';
            element.querySelectorAll('input').forEach(input => input.removeAttribute('required'));
        });
    }

    // Display and require inputs for selected type
    function onSwitchType() {
        typeErrors = [];
        toggle();

        if (switcher.value > 0) {
            const selectedType = switcher.options[switcher.value].text.trim();
            const selectedElement = document.getElementById(selectedType);
            selectedElement.style.display = 'flex';
            selectedElement.querySelectorAll('input').forEach(input => {
                input.setAttribute('required', 'required');
                if(input.value || input.value !== ""){
                    if (isNaN(input.value) || parseFloat(input.value) <= 0) {
                        typeErrors.push(`${input.getAttribute('name')} must be a positive number.`);
                    }
                }else{
                    typeErrors.push(`${input.getAttribute('name')} is required`);
                } 
                
            });
        } else {
            typeErrors.push("Please select a product type.");
        }

        displayErrors();
    }

    switcher.addEventListener('change', (e) => {
        e.preventDefault();
        onSwitchType();
    });

    // Form submission handling
    document.getElementById('product_form').addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const visibleInputs = [];
        typeErrors = [];
        formErrors = [];
        validationErrors = [];

        // Basic validations
        const sku = formData.get('sku').trim();
        if (!sku) validationErrors.push('SKU cannot be empty');

        const name = formData.get('name').trim();
        if (!name) validationErrors.push('Name cannot be empty');

        const price = formData.get('price').trim();
        if (!price || isNaN(price) || parseFloat(price) < 0) {
            validationErrors.push('Price must be a positive number');
        }

        const type = formData.get("type");
        if (type > 0) {
            onSwitchType();
            document.querySelectorAll('.types input').forEach(input => {
                if (input.offsetParent !== null) {
                    visibleInputs.push(input.getAttribute('name'));
                }
            });
        } else {
            typeErrors.push("Please select a product type.");
        }

        // Append visible inputs to form data
        formData.append('visibleInputs', JSON.stringify(visibleInputs));
        const inputData = Object.fromEntries(formData.entries());

        // Submit form data if no errors
        if (formErrors.length === 0 && typeErrors.length === 0 && validationErrors.length === 0) {
            try {
                const response = await fetch('save_product.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(inputData)
                });
                const resData = await response.json();

                if (response.ok && resData.success) {
                    window.location.href = './';
                } else {
                    formErrors = formErrors.concat(resData.errors || [response.statusText]);
                }
            } catch (error) {
                formErrors.push("An error occurred while saving the product. Please try again.");
                console.error('Error:', error); // Log for debugging
            }
        }

        displayErrors();
    });

    // Function to display error messages
    function displayErrors() {
        errorContainer.innerHTML = ''; // Clear previous errors
        [...validationErrors, ...typeErrors, ...formErrors].forEach(error => {
            const errorElement = document.createElement('p');
            errorElement.textContent = error;
            errorContainer.appendChild(errorElement);
        });
    }

    // Initial hide of type-specific inputs
    toggle();
});
