( () => {
    'use strict'
 
    const filterButtons = document.querySelectorAll('.wp-block-categories a');
    const portfolioItems = document.querySelectorAll('.wp-block-post');
    let currentActiveFilter = null;

    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const category = this.getAttribute('href').split('/').filter(Boolean).pop();
            
            // Se clicco sullo stesso filtro già attivo
            if ( currentActiveFilter === category ) {
                // Resetta mostrando tutto
                portfolioItems.forEach( item => item.classList.remove( 'd-none' ) );

                this.classList.remove('active');
                currentActiveFilter = null;
                return;
            }
            
            // Applica il nuovo filtro
            portfolioItems.forEach( item => {
                
                if( item.classList.contains( `jetpack-portfolio-type-${category}` ) ) {
                    item.classList.remove( 'd-none' );
                } else {
                    item.classList.add( 'd-none' );
                }
            });
            
            // Aggiorna lo stato attivo
            filterButtons.forEach( btn => btn.classList.remove( 'active' ) );
            this.classList.add( 'active' );
            currentActiveFilter = category;
        });
    });

     
    document.querySelectorAll( '.wp-block-read-more' ).forEach( button => {
        button.addEventListener( 'click', async ( e ) => {
            e.preventDefault();
     
            const popupContainer = document.querySelector( '.popup' );
            const popupContent = document.querySelector( '.popup-content' );

  
            openPopup();

            popupContent.innerHTML = `<div class="loading-message">Loading…</div>`;

             try {
                // Fetch the project data from the REST API.
                const response = await fetch(
                    `/wp-json/wp/v2/jetpack-portfolio/${button.dataset.projectId}?_fields=title,content,slug`
                );

                if ( ! response.ok ) {
                    throw new Error('Network response was not ok');
                }

                 
                const data = await response.json();
                 
                // Update the URL in the browser without reloading the page.
                history.pushState({ project: data.slug }, '', button.href );


                popupContent.innerHTML= `<h2 id="popup-title">${data.title.rendered}</h2>
                    <div class="entry-content">${data.content.rendered}</div>`;

                popupContainer.setAttribute( 'aria-hidden', 'false' );
            } catch ( error ) {

                popupContent.innerHTML = `
                <p class="has-text-align-center">
                    ${wp.i18n.__('Errore di caricamento', 'twenties')}
                </p>`;
                console.error('Error fetching project:', error);
            
            }

            // Close the popup when clicking outside of it.
            popupContainer.addEventListener('click', function( e ) {
                if ( e.target.closest( '.popup-close' ) || e.target === popupContainer ) {
                    closePopup();
                }
            });
        });
    });


    window.addEventListener( 'popstate', function(e) {
        // Se torniamo alla pagina portfolio (senza slug progetto), chiudi il popup
        if (!window.location.pathname.includes('/portfolio/')) {
           closePopup(); // Chiudi il popup
        }
        // Altrimenti, gestisci il "forward" (es: se l'utente clicca AVANTI)
        else if (e.state && e.state.project) {
      
           openPopup(e.state.project); // Riapri il popup con il progetto corretto
        }
    } );
 
    function closePopup() {
        const popupContainer = document.querySelector( '.popup' );
        const popupContent = popupContainer.querySelector('.popup-content');
    
        // Ripristina il contenuto originale (vuoto) mantenendo la struttura esistente
        if ( popupContent ) {
            popupContent.innerHTML = '';
        }

        popupContainer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden', 'layer-open');
        popupContainer.classList.add('d-none');
        history.replaceState( null, '', '/portfolio/' );
    }

    function openPopup( ) {
        const popupContainer = document.querySelector( '.popup' );  

        document.body.classList.add( 'overflow-hidden', 'layer-open' );
        popupContainer.classList.remove( 'd-none' );
    }
 

} )();















 