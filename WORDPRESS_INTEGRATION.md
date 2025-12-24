# 📱 Integración en WordPress - Radio Player

> [!WARNING]
> **¿Tu sitio web tiene candadito (HTTPS)?**
> Si tu web es segura (https), el navegador **bloqueará** el audio porque el servidor de radio es http (sin 's').
> 
> **SOLUCIÓN:** Usa la **Opción 3 (Botón Popup)**. Es la única forma de escuchar radio HTTP en un sitio HTTPS sin certificados SSL complejos.

---

## Opción 3: Botón Popup (SOLUCIÓN DEFINITIVA) 🏆

Esta es la opción más robusta. Abre el reproductor en una ventanita separada. **Funciona siempre**, incluso en sitios seguros (HTTPS).

### Código para copiar:

```html
<!-- Botón Popup Radio -->
<div style="text-align: center; margin: 20px;">
    <a href="javascript:void(0);" 
       onclick="window.open('http://72.62.86.94/embed.html', 'RadioPlayer', 'width=380,height=250,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=0'); return false;"
       style="display: inline-flex; align-items: center; gap: 12px; background: linear-gradient(135deg, #e91e63 0%, #9c27b0 100%); color: white; padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: bold; font-family: 'Segoe UI', system-ui, sans-serif; box-shadow: 0 5px 15px rgba(233, 30, 99, 0.4); transition: transform 0.2s;">
       <span style="font-size: 20px;">▶</span>
       <span>ESCUCHAR EN VIVO</span>
    </a>
    <div style="margin-top: 8px; font-size: 12px; color: #888;">Se abrirá en una nueva ventana</div>
</div>
```

**Instrucciones:**
1. Añade un bloque **"HTML Personalizado"**.
2. Pega el código.
3. Al hacer click, se abrirá el player sin bloqueos.

---

## Opción 1: Iframe (Solo para sitios HTTP)

Esta es la mejor opción. Carga un reproductor optimizado desde tu servidor de radio.
**Requiere que hagas redeploy en Hostinger para activar el archivo `embed.html`.**

### Código para copiar:

```html
<iframe src="http://72.62.86.94/embed.html" 
        width="100%" 
        height="100" 
        frameborder="0" 
        scrolling="no" 
        allow="autoplay"
        style="border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
</iframe>
```

**Instrucciones:**
1. En WordPress, añade un bloque **"HTML Personalizado"**.
2. Pega el código de arriba.
3. Guarda y publica.

---

## Opción 2: Reproductor Nativo (Sin Redeploy)

Si quieres probarlo YA sin esperar al redeploy, copia y pega este código completo directamente en tu WordPress.

### Código para copiar:

```html
<div style="font-family: system-ui, sans-serif; background: #fff; padding: 15px 25px; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 20px; max-width: 450px; margin: 0 auto; border: 1px solid #eee;">
    
    <!-- Botón Play -->
    <button id="wp-play-btn" style="width: 50px; height: 50px; border-radius: 50%; border: none; background: linear-gradient(135deg, #e91e63 0%, #9c27b0 100%); color: white; font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(233, 30, 99, 0.3);">
        <span id="wp-play-icon">▶</span>
        <span id="wp-pause-icon" style="display:none;">⏸</span>
        <span id="wp-load-icon" style="display:none;">⏳</span>
    </button>
    
    <!-- Info -->
    <div style="flex-grow: 1;">
        <div style="font-weight: 700; color: #333; font-size: 16px;">Radio En Vivo</div>
        <div style="font-size: 12px; color: #666; display: flex; align-items: center; gap: 6px;">
            <span id="wp-live-dot" style="width: 8px; height: 8px; background-color: #ccc; border-radius: 50%; display: inline-block;"></span>
            <span id="wp-status">Click para escuchar</span>
        </div>
    </div>
</div>

<audio id="wp-audio" preload="none" crossorigin="anonymous"></audio>

<script>
(function() {
    var audio = document.getElementById('wp-audio');
    var btn = document.getElementById('wp-play-btn');
    var playIcon = document.getElementById('wp-play-icon');
    var pauseIcon = document.getElementById('wp-pause-icon');
    var loadIcon = document.getElementById('wp-load-icon');
    var dot = document.getElementById('wp-live-dot');
    var status = document.getElementById('wp-status');
    var isPlaying = false;
    
    var streamUrl = 'http://72.62.86.94/radio.aac';

    function setSt(state) {
        playIcon.style.display = state==='idle'?'block':'none';
        pauseIcon.style.display = state==='play'?'block':'none';
        loadIcon.style.display = state==='load'?'block':'none';
        
        if(state==='play') {
            dot.style.backgroundColor = '#4caf50';
            status.textContent = 'En Vivo • ON AIR';
        } else if(state==='load') {
            dot.style.backgroundColor = '#ccc';
            status.textContent = 'Conectando...';
        } else {
            dot.style.backgroundColor = '#ccc';
            status.textContent = 'Click para escuchar';
        }
    }

    btn.addEventListener('click', function() {
        if(isPlaying) {
            audio.pause();
            isPlaying = false;
            setSt('idle');
            audio.src = ''; 
        } else {
            setSt('load');
            audio.src = streamUrl;
            audio.play().then(function() {
                isPlaying = true;
                setSt('play');
            }).catch(function(e) {
                console.error(e);
                setSt('idle');
                status.textContent = 'Error de conexión';
                isPlaying = false;
            });
        }
    });
})();
</script>
```

**Instrucciones:**
1. Añade un bloque **"HTML Personalizado"** en WordPress.
2. Pega TODO el código.
3. Publica. Funcionará inmediatamente (siempre que tu radio esté transmitiendo).
