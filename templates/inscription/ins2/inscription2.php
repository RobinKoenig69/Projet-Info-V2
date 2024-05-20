<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="inscription2.css" />
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
              <div class="text-wrapper">Suivant</div>
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
        <div class="profil-picture">
            <div class="overlap-3">
                <div class="ellipse"></div>
                <div class="ellipse-2"></div>
                <div class="icon-add-circled" onclick="document.getElementById('fileInput').click();">
                    <img src="../../../images/plus.svg" alt=""/>
                </div>
            </div>
            <input type="file" id="fileInput" style="display: none;" onchange="handleFileSelect(event)"/>
        </div>
        <div class="overlap-wrapper">
            <div class="div-wrapper" onclick="document.getElementById('fileInputProfile').click();">
                <p class="p">Ajouter une photo de profil</p>
            </div>
            <input type="file" id="fileInputProfile" style="display:none;" onchange="handleFileSelect(event)"/>
        </div>
        
      </div>
    </div>
  </body>
</html>
<script>
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
    </script>
    
    
