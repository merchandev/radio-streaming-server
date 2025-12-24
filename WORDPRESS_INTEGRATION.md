# 📱 Integración en WordPress - Radio Player

Aquí tienes el código optimizado para que el reproductor inicie **DE INMEDIATO** y en el mismo widget.

---

## Opción Recomendada: Iframe Optimizado 🚀

Esta opción carga el reproductor desde tu servidor. Es la más rápida y limpia.

### Código para copiar:

```html
<iframe src="http://72.62.86.94/embed.html" 
        width="100%" 
        height="500" 
        frameborder="0" 
        scrolling="no" 
        allow="autoplay; encrypted-media"
        style="border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); background: transparent;">
</iframe>
```

**Instrucciones:**
1. Añade un bloque **"HTML Personalizado"** en WordPress.
2. Pega el código.
3. Guarda y publica.

> [!NOTE]
> **Autoplay**: Los navegadores modernos bloquean el sonido automático hasta que el usuario hace clic. El usuario deberá hacer clic en Play la primera vez.

---

## Opción 2: Código Directo (Sin Iframe)

Si prefieres pegar todo el código directo en tu página:

```html
<div style="font-family: system-ui, sans-serif; background: #fff; padding: 15px 25px; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 20px; max-width: 450px; margin: 0 auto; border: 1px solid #eee;">
    <button id="wp-play-btn" style="width: 50px; height: 50px; border-radius: 50%; border: none; background: linear-gradient(135deg, #e91e63 0%, #9c27b0 100%); color: white; font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(233, 30, 99, 0.3);">▶</button>
    <div style="flex-grow: 1;">
        <div style="font-weight: 700; color: #333;">Radio En Vivo</div>
        <div id="wp-status" style="font-size: 12px; color: #666;">Click para escuchar</div>
    </div>
</div>

<audio id="wp-audio" preload="auto"></audio>

<script>
(function() {
    var a = document.getElementById('wp-audio');
    var b = document.getElementById('wp-play-btn');
    var s = document.getElementById('wp-status');
    var playing = false;
    
    // URL directa para máxima velocidad
    var url = 'http://72.62.86.94/radio.aac';

    b.addEventListener('click', function() {
        if(playing) {
            a.pause();
            playing = false;
            b.innerHTML = '▶';
            s.textContent = 'Pausado';
            a.src = ''; // Detener carga
        } else {
            b.innerHTML = '⏳';
            s.textContent = 'Conectando...';
            a.src = url;
            a.play().then(() => {
                playing = true;
                b.innerHTML = '⏸';
                s.textContent = 'En Vivo • ON AIR';
            }).catch((e) => {
                console.error(e);
                s.textContent = 'Error: Verifica HTTPS mixtos';
                b.innerHTML = '▶';
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
