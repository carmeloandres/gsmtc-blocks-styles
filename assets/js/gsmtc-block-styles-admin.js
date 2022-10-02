function showHideStyles(postId){
    let boton = document.getElementById('gsmtc_block_style_button_'+postId);
    if (boton.innerHTML.includes('Mostrar'))
        boton.innerHTML = boton.innerHTML.replace('Mostrar','Ocultar');
    else
        boton.innerHTML = boton.innerHTML.replaceAll('Ocultar','Mostrar');
    
    let elemento = document.getElementById('gsmtc_admin_accordeon_body_id_'+postId);
    elemento.classList.toggle('gsmtc-closed');
}

function anyadirEstilos(){
    console.log('settings : ',wpApiSettings);
    
    let datosEstilosNuevo = new FormData();
    datosEstilosNuevo.append('etiqueta',document.getElementById('gsmtc_admin_input_etiqueta_nueva').value);
    datosEstilosNuevo.append('estilos',document.getElementById('gsmtc_admin_input_textarea_nueva').value);
    datosEstilosNuevo.append('id_post',document.getElementById('gsmtc_admin_post_id').value);
    datosEstilosNuevo.append('block_name',wpApiSettings.block_name);
    datosEstilosNuevo.append('abc_id_resultados',1);
    
    const headers = new Headers({
        'X-WP-Nonce': wpApiSettings.nonce
    });
    
    fetch(wpApiSettings.rest_url,{
        method: 'POST',
        headers: headers,
        //                    credentials: 'same-origin'                    
        body: datosEstilosNuevo
    })
    .then (response => response.json())
    .then (response => {
        console.log (response);
        
    });
}
