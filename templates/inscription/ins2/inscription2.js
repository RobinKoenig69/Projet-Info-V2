function handleFileSelect(event) {
    var files = event.target.files; // FileList object
    var output = document.querySelector('.profil-picture .ellipse-2'); // Ajustez si nécessaire

    for (var i = 0, f; f = files[i]; i++) {
        if (!f.type.match('image.*')) {
            continue;
        }

        var reader = new FileReader();

        reader.onload = (function(theFile) {
            return function(e) {
                output.style.backgroundImage = 'url(' + e.target.result + ')';
                output.style.backgroundSize = 'cover';
                output.style.backgroundPosition = 'center';
            };
        })(f);

        reader.readAsDataURL(f);
    }
}