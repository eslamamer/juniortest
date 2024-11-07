document.getElementById('delete-product-btn')?.addEventListener('click', function (e) {
    e.preventDefault();
    const delItems = document.querySelectorAll('.delete-checkbox:checked');
    const delIds = Array.from(delItems).map(checked => checked.getAttribute('data-id'));

    if (delIds.length > 0) {
        fetch('delete_products.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids: delIds })
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                throw new Error('Response not OK');
            }
        })
        .catch(error => console.error('Error:', error)); // Catching errors
    }
})