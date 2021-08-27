function confirmDelete(id) {
    if (confirm('Souhaité vous vraiment retirer cette palette du stock ?')) {
        document.location.href = "https://localhost:8000/intranet/palette/supp/" + id
    }
}