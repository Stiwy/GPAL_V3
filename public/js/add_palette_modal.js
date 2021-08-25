function listRef(stingRed) {
    tabRefs = stingRed.split(",");

    let value = document.getElementById('palette_reference').value;

    if(!tabRefs.includes(value)) {
        document.getElementById('palette_reference_help').innerHTML = "Cette référence n'est pas enregistré souhaité vous l'ajouter ? <a class='btn btn-custom-primary btn-sm' onclick='addRef()'>Ajouter</a>";
        document.getElementById('palette_reference_help').style.color = "red";
        document.getElementById('add_palette_ref').value = "no-registered";
        document.getElementById('palette_submit').setAttribute('disabled', "true");
        
    } else {
        document.getElementById('palette_reference_help').innerHTML = "Cette référence est bien enregistré.";
        document.getElementById('palette_reference_help').style.color = "green";
        document.getElementById('add_palette_ref').value = "registered";
        document.getElementById('palette_submit').removeAttribute('disabled');
    }
}

function addRef()
{
    document.getElementById('palette_reference_help').innerHTML = "Cette référence est bien enregistré.";
    document.getElementById('palette_reference_help').style.color = "green";
    document.getElementById('add_palette_ref').value = "registered";
    document.getElementById('palette_submit').removeAttribute('disabled');
}

// On load file add_modal_palette.html.twig
document.getElementById('add_palette_link').value = document.location.href;