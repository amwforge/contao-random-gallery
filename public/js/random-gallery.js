if (typeof jQuery !== 'undefined') {
    $(document).ready(function() {
        const rgMore = document.querySelector(".amw-rg-more a");
        if (rgMore != null) {
            preloadImage('/bundles/amwforgecontaorandomgallery/img/spinner-solid-grey.svg');
            
            rgMore.addEventListener('click', function() {
                event.preventDefault();
                const button = document.querySelector(".amw-rg-more");
                button.classList.add('hidden');
                const loading = document.querySelector(".loading");
                loading.classList.add('visible');
                setTimeout(loadImages, 10);
            });
        }
        
        function loadImages() {
            const gallery = document.querySelector(".content-amw-random-gallery .image-container");
            const button = document.querySelector(".amw-rg-more");
            const loading = document.querySelector(".loading");
            const url = window.location.pathname +'?ajax=AmwRandomGallery&action=moreImages'; 
            const xhttp = new XMLHttpRequest();
            
            xhttp.onload = function() {
                const response = JSON.parse(this.responseText);
                
                if (response.images) {
                    // Bilder ausgeben
                    response.images.forEach(function(image) {
                        let div = document.createElement('div');
                        div.classList.add('amw-rg-image');
                        div.classList.add('more');
                        div.innerHTML = image;
                        gallery.append(div);
                    });
                    
                    // Lightbox-Event einfügen
                    $('.content-amw-random-gallery').find('a[data-lightbox]').each(function() {
                        $(this).colorbox({
                            maxWidth: '90%',
                            maxHeight: '90%',
                            rel: 'colorbox',
                        });
                    });
            
                    // Ladeanimation beenden
                    loading.classList.remove('visible');
                    button.classList.remove('hidden');
                }
            }
            xhttp.open("POST", url, false);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhttp.send();
        }
        
        function preloadImage(url) {
            let img = new Image();
            img.src = url;
        }
    })
}