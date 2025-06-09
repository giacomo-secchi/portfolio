( () => {
    'use strict'
 
    const filterButtons = document.querySelectorAll('.wp-block-categories-list a');
    const portfolioItems = document.querySelectorAll('.wp-block-post');
    let currentActiveFilter = null;

    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const category = this.getAttribute('href').split('/').filter(Boolean).pop();
            
            // Se clicco sullo stesso filtro già attivo
            if ( currentActiveFilter === category ) {
                // Resetta mostrando tutto
                portfolioItems.forEach( item => {
                    item.classList.remove( 'd-none' );
                } );

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
                 
  
            openPopup();

            //popupContainer.innerHTML = '<div class="loading-message">Loading...</div>';

             try {
                const response = await wp.apiFetch({
                    path: `/wp/v2/jetpack-portfolio/${button.dataset.projectId}?_fields=title,content,slug`,
                    method: 'GET'
                });
                 
                // Update the URL in the browser without reloading the page.
                history.pushState({ project: response.slug }, '', button.href );

            

                popupContainer.insertAdjacentHTML('afterbegin', `
                    <div class="popup-content">
                        <h2>${response.title.rendered}</h2>
                        <div class="entry-content">${response.content.rendered}</div>
                    </div>
                `);

                popupContainer.setAttribute( 'aria-hidden', 'false' );
            } catch ( error ) {

                popupContainer.innerHTML = `
                <p class="has-text-align-center">
                    ${wp.i18n.__('Errore di caricamento', 'twenties')}
                </p>
            `;
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
            debugger;
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















 