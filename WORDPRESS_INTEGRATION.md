# 📱 Integración en WordPress - Radio Luxury Glass (V6)

Esta es la versión definitiva y más robusta del reproductor. Diseñada para funcionar en WordPress, incluso con problemas de "Contenido Mixto" (HTTPS/HTTP).

---

## 💎 Opción 1 (Recomendada): Widget "Luxury Glass" V6

Este widget es autocontenido, estéticamente premium y maneja errores de forma inteligente.

### Características V6:
*   ✅ **Diseño Luxury**: Cristal esmerilado, animaciones y ecualizador.
*   ✅ **Anti-Spam de Consola**: No llena el navegador de errores rojos.
*   ✅ **Guía de Seguridad**: Detecta si el navegador bloquea el audio y le dice al usuario qué hacer (clic en el candado).
*   ✅ **Auto-Limpieza**: Detiene conexiones fallidas inmediatamente.

### Instrucciones de Instalación:

1.  En tu WordPress, ve a **Apariencia > Widgets** o edita la página con Gutenberg/Elementor.
2.  Añade un bloque **"HTML Personalizado"**.
3.  Copia y pega **TODO** el contenido del archivo `wordpress-widget-snippet.html` que está en este repositorio.
4.  Guarda los cambios.

---

## ⚠️ Nota sobre Seguridad (HTTPS)

Tu página web es **HTTPS** (Segura 🔒) y la radio transmite en **HTTP** (Estándar).

Por defecto, los navegadores (Chrome, Edge, Safari) bloquean el audio para "protegerte". Esto **no es un error del widget**, es una norma de internet.

**Para que suene:**
El usuario debe hacer clic en el **candado 🔒** o **escudo 🛡️** de la barra de direcciones y permitir el **"Contenido Inseguro"** para tu sitio web. El Widget V6 detecta esto y se lo recuerda al usuario amablemente si falla la conexión.

---

## Opción 2: Iframe (Alternativa)

Si prefieres usar un iframe simple (menos control sobre errores):

```html
<iframe src="http://72.62.86.94/embed.html" 
        width="100%" 
        height="550" 
        frameborder="0" 
        scrolling="no" 
        allow="autoplay; encrypted-media"
        style="border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); background: transparent;">
</iframe>
```
