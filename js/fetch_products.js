async function fetchProducts() {
    const controlDiv = document.querySelector('.control'); // Select the control div

    try {
        const res = await fetch('display_products.php', {
            method: 'GET',
            headers: { "Content-Type": "application/json" },
        });

        if (res.ok) {
            const data = await res.json();
            const itemsAttrs = data.display;
            
            const fragment = document.createDocumentFragment(); // Use a fragment as items container

            itemsAttrs.forEach(itemAttrs => {
                // Create new item div
                const itemDiv = document.createElement('div');
                itemDiv.classList.add('item');

                // Create checkbox and set its attributes
                const deleteCheckbox = document.createElement('input');
                deleteCheckbox.type = 'checkbox';
                deleteCheckbox.name = 'delete';
                deleteCheckbox.classList.add('delete-checkbox');
                deleteCheckbox.setAttribute('data-id', itemAttrs[0]);

                // Create item-data div
                const itemDataDiv = document.createElement('div');
                itemDataDiv.classList.add('item-data');

                // Create and append product description as a paragraph
                itemAttrs.forEach((attr, i) => {
                    if (i === 0) return;
                    const p = document.createElement('p');
                    p.textContent = attr;
                    itemDataDiv.appendChild(p);
                });

                // Append the checkbox and item-data div to the item div
                itemDiv.appendChild(deleteCheckbox);
                itemDiv.appendChild(itemDataDiv);

                // Add the item div to the fragment
                fragment.appendChild(itemDiv);
            });

            // Insert all items at once after the control div
            controlDiv.parentNode.insertBefore(fragment, controlDiv.nextSibling);

        } else {
            console.log("Error: ", await res.json());
        }
    } catch (e) {
        console.log("Fetch error: ", e);
    }
}

// Call the fetch function when the page loads
document.addEventListener('DOMContentLoaded', () => {
    fetchProducts();
});
