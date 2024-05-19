<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="inscription3.css" />
  </head>
  <body>
    <div class="page-inscription">
      <div class="div">
        <div class="overlap"><div class="rectangle"></div></div>
        <div class="overlap-group">
          <div class="se-connecter">
            <div class="overlap-group-2">
              <div class="rectangle-2"></div>
              <div class="text-wrapper">S’inscrire</div>
            </div>
          </div>
          <div class="se-connecter">
            <div class="overlap-group-2">
              <div class="rectangle-2"></div>
              <div class="text-wrapper">S’inscrire</div>
            </div>
          </div>
        </div>
        <div class="overlap-2">
          <div class="logo"><img class="logo-omnes-education" src="../../../images/Logo_omnes.png" /></div>
          <div class="navbtn">
            <div class="rectangle-3"></div>
            <div class="rectangle-4"></div>
            <div class="rectangle-5"></div>
          </div>
        </div>
        <div class="overlap-wrapper">
            <img class="permis-de-conduire" src="../../../images/plus.svg" onclick="document.getElementById('fileInputDriverLicense').click();" alt="Upload Icon" />        
            <input type="file" id="fileInputDriverLicense" style="display:none;" onchange="handleDriverLicenseUpload(event)"/>
        </div>
        
        <div class="overlap-wrapper">
            <div class="div-wrapper" onclick="document.getElementById('fileInputDriverLicense').click();">
                <p class="p">Ajouter votre permis de conduire</p>
            </div>
            <input type="file" id="fileInputDriverLicense" style="display:none;" onchange="handleDriverLicenseUpload(event)"/>
        </div>
      </div>
    </div>
  </body>
</html>
<script>
    function handleDriverLicenseUpload(event) {
        var files = event.target.files;
        if (files.length === 0) {
            alert('No file selected!');
            return;
        }
        var file = files[0];
        if (file.type.match('image.*')) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Assuming there's an element to display the image
                var displayArea = document.querySelector('.permis-de-conduire');
                displayArea.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            alert('Please select an image file.');
        }
    }
    </script>