<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal IA - Interfaz Neural</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap');

        :root {
            --term-color: #0ff; 
            --term-bg: #050505;
            --term-glow: 0 0 12px rgba(0, 255, 255, 0.6);
        }

        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: var(--term-bg);
            color: #0ff;
            font-family: 'Share Tech Mono', monospace;
            overflow: hidden;
        }

        .scanlines {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: linear-gradient(
                to bottom,
                rgba(255,255,255,0),
                rgba(255,255,255,0) 50%,
                rgba(0,0,0,0.2) 50%,
                rgba(0,0,0,0.2)
            );
            background-size: 100% 4px;
            pointer-events: none;
            z-index: 50;
            opacity: 0.5;
        }

        #canvas-container {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 1;
        }

        #ascii-animation-container {
            position: absolute;
            top: -5%;
            left: 0;
            width: 100%;
            height: 85%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 5; 
            pointer-events: none;
            perspective: 1000px;
            background: radial-gradient(circle at center, rgba(5,5,5,1) 0%, rgba(5,5,5,0.8) 15%, rgba(5,5,5,0) 45%);
        }

        .ascii-art {
            font-family: "Consolas", "Monaco", "Courier New", monospace;
            white-space: pre;
            text-align: center;
            line-height: 1.1;
            letter-spacing: 0px;
            font-size: min(1.2vw, 1.6vh); 
            transition: opacity 0.5s ease;
            opacity: 0;
            transform-style: preserve-3d;
            will-change: transform;
        }

        #ui-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            pointer-events: none;
            z-index: 10;
        }

        #chat-wrapper {
            background: transparent;
            width: 100%;
            pointer-events: auto;
            display: flex;
            flex-direction: column;
        }

        #chat-history {
            height: 25vh;
            min-height: 150px;
            padding: 1.5rem 2rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            scrollbar-width: thin;
            scrollbar-color: #0ff transparent;
        }

        #chat-history::-webkit-scrollbar { width: 6px; }
        #chat-history::-webkit-scrollbar-thumb { background: #0ff; }

        .message {
            max-width: 85%;
            text-shadow: var(--term-glow);
            line-height: 1.5;
            animation: fadeIn 0.3s ease-in;
        }

        .msg-user { align-self: flex-end; color: #f0f; text-shadow: 0 0 10px rgba(255, 0, 255, 0.7); }
        .msg-ai { align-self: flex-start; color: #0ff; }
        .msg-system { align-self: center; color: #ff0; font-size: 0.9em; opacity: 0.8; text-shadow: 0 0 10px rgba(255,255,0,0.5); }

        #input-area {
            padding: 1rem 2rem 2rem 2rem;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        #chat-input {
            flex-grow: 1;
            background: rgba(0, 20, 20, 0.6);
            border: 1px solid #0ff;
            color: #0ff;
            padding: 1rem;
            font-family: 'Share Tech Mono', inherit;
            font-size: 1.2rem;
            outline: none;
            box-shadow: inset 0 0 10px rgba(0, 255, 255, 0.2);
            transition: all 0.3s;
        }

        #chat-input:focus { box-shadow: inset 0 0 20px rgba(0, 255, 255, 0.4), 0 0 10px rgba(0, 255, 255, 0.5); }

        button {
            background: rgba(0, 20, 20, 0.6);
            border: 1px solid #0ff;
            color: #0ff;
            padding: 1rem 2rem;
            cursor: pointer;
            font-family: 'Share Tech Mono', inherit;
            font-size: 1.2rem;
            text-transform: uppercase;
            transition: all 0.2s;
            text-shadow: var(--term-glow);
        }

        button:hover { background: #0ff; color: #000; box-shadow: 0 0 15px #0ff; }
        button:disabled { border-color: #333; color: #333; background: transparent; cursor: not-allowed; text-shadow: none; box-shadow: none; }

        .cursor::after { content: '█'; animation: blink 1s step-start infinite; }
        @keyframes blink { 50% { opacity: 0; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="scanlines"></div>
    <div id="canvas-container"></div>
    <div id="ascii-animation-container">
        <pre id="ascii-art-target" class="ascii-art"></pre>
    </div>

    <div id="ui-layer">
        <div id="chat-wrapper">
            <div id="chat-history"></div>
            <div id="input-area">
                <span class="text-xl font-bold">></span>
                <input type="text" id="chat-input" placeholder="Escribe tu mensaje..." onkeypress="handleKeyPress(event)">
                <button id="btn-send" onclick="enviarMensaje()">Enviar</button>
            </div>
        </div>
    </div>

    <script>
        const apiKey = '{{ env("GEMINI_API_KEY") }}'; 
        let isSpeaking = false;
        let isWaitingResponse = false;
        const chatHistory = document.getElementById('chat-history');
        const chatInput = document.getElementById('chat-input');
        const btnSend = document.getElementById('btn-send');
        
        let nombreVozDeseada = ""; 
        let vocesDisponibles = [];

        let scene, camera, renderer, starParticles, dustParticles;
        let galaxyGroup, galaxyMaterial; 
        let mouseX = 0, mouseY = 0;
        let windowHalfX = window.innerWidth / 2;
        let windowHalfY = window.innerHeight / 2;

        const shaderUtils = `
        float random (vec2 st) {
          return fract(sin(dot(st.xy, vec2(12.9898, 78.233))) * 43758.5453123);
        }

        vec3 scatter (vec3 seed) {
          float u = random(seed.xy);
          float v = random(seed.yz);
          float theta = u * 6.28318530718;
          float phi = acos(2.0 * v - 1.0);

          float sinTheta = sin(theta);
          float cosTheta = cos(theta);
          float sinPhi = sin(phi);
          float cosPhi = cos(phi);

          float x = sinPhi * cosTheta;
          float y = sinPhi * sinTheta;
          float z = cosPhi;

          return vec3(x, y, z);
        }
        `;

        function init3D() {
            const container = document.getElementById('canvas-container');
            scene = new THREE.Scene();
            scene.fog = new THREE.FogExp2(0x050505, 0.0015);
            camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            camera.position.z = 15;

            renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setPixelRatio(window.devicePixelRatio); 
            renderer.setSize(window.innerWidth, window.innerHeight);
            container.appendChild(renderer.domElement);

            const starGeo = new THREE.BufferGeometry();
            const starCount = 1500;
            const starPos = new Float32Array(starCount * 3);
            for(let i = 0; i < starCount * 3; i++) {
                starPos[i] = (Math.random() - 0.5) * 60;
            }
            starGeo.setAttribute('position', new THREE.BufferAttribute(starPos, 3));
            const starMat = new THREE.PointsMaterial({
                size: 0.06, color: 0xffffff, transparent: true, opacity: 0.8
            });
            starParticles = new THREE.Points(starGeo, starMat);
            scene.add(starParticles);

            const dustGeo = new THREE.BufferGeometry();
            const dustCount = 3000;
            const dustPos = new Float32Array(dustCount * 3);
            for(let i = 0; i < dustCount * 3; i++) {
                dustPos[i] = (Math.random() - 0.5) * 50;
            }
            dustGeo.setAttribute('position', new THREE.BufferAttribute(dustPos, 3));
            const dustMat = new THREE.PointsMaterial({
                size: 0.02, color: 0x88ccff, transparent: true, opacity: 0.3
            });
            dustParticles = new THREE.Points(dustGeo, dustMat);
            scene.add(dustParticles);

            const ctx = document.createElement("canvas").getContext("2d");
            ctx.canvas.width = ctx.canvas.height = 32;
            ctx.fillStyle = "#000";
            ctx.fillRect(0, 0, 32, 32);

            let grd = ctx.createRadialGradient(16, 16, 0, 16, 16, 16);
            grd.addColorStop(0.0, "#fff");
            grd.addColorStop(1.0, "#000");
            ctx.fillStyle = grd;
            ctx.beginPath(); ctx.rect(15, 0, 2, 32); ctx.fill();
            ctx.beginPath(); ctx.rect(0, 15, 32, 2); ctx.fill();

            grd = ctx.createRadialGradient(16, 16, 0, 16, 16, 16);
            grd.addColorStop(0.1, "#ffff");
            grd.addColorStop(0.6, "#0000");
            ctx.fillStyle = grd;
            ctx.fillRect(0, 0, 32, 32);

            const alphaMap = new THREE.CanvasTexture(ctx.canvas);

            const count = 10000; 
            const galaxyGeometry = new THREE.BufferGeometry();
            const galaxyPosition = new Float32Array(count * 3);
            const galaxySeed = new Float32Array(count * 3);
            const galaxySize = new Float32Array(count);

            for (let i = 0; i < count; i++) {
              if (i === 0) {
                galaxyPosition[i * 3] = 0;
                galaxySeed[i * 3 + 0] = 0;
                galaxySeed[i * 3 + 1] = 0;
                galaxySeed[i * 3 + 2] = 0;
                galaxySize[i] = 30.0; 
              } else {
                galaxyPosition[i * 3] = i / count;
                galaxySeed[i * 3 + 0] = Math.random();
                galaxySeed[i * 3 + 1] = Math.random();
                galaxySeed[i * 3 + 2] = Math.random();
                galaxySize[i] = Math.random() * 3.5 + 1.2; 
              }
            }

            galaxyGeometry.setAttribute("position", new THREE.BufferAttribute(galaxyPosition, 3));
            galaxyGeometry.setAttribute("size", new THREE.BufferAttribute(galaxySize, 1));
            galaxyGeometry.setAttribute("seed", new THREE.BufferAttribute(galaxySeed, 3));

            const innColor = new THREE.Color("#007070"); 
            const outColor = new THREE.Color("#8e0ae6"); 

            galaxyMaterial = new THREE.RawShaderMaterial({
              uniforms: {
                uTime: { value: 0 },
                uSize: { value: renderer.getPixelRatio() * 2.0 * 6.0 },
                uBranches: { value: 5.0 }, 
                uRadius: { value: 2.2 }, 
                uSpin: { value: 5.0 }, 
                uRandomness: { value: 1.05 }, 
                uAlphaMap: { value: alphaMap },
                uColorInn: { value: innColor },
                uColorOut: { value: outColor },
              },
              vertexShader: `
                precision highp float;
                attribute vec3 position;
                attribute float size;
                attribute vec3 seed;
                uniform mat4 projectionMatrix;
                uniform mat4 modelViewMatrix;

                uniform float uTime;
                uniform float uSize;
                uniform float uBranches;
                uniform float uRadius;
                uniform float uSpin;
                uniform float uRandomness;

                varying float vDistance;

                #define PI  3.14159265359
                #define PI2 6.28318530718

                #include <random, scatter>

                void main() {
                  vec3 p = position;
                  
                  if (size > 20.0) {
                      vDistance = 0.0;
                      vec4 mvp = modelViewMatrix * vec4(0.0, 0.0, 0.0, 1.0);
                      gl_Position = projectionMatrix * mvp;
                      gl_PointSize = (10.0 * size * uSize) / -mvp.z;
                      return;
                  }

                  float distFromCenter = 0.04 + pow(p.x, 1.15) * 0.96; 
                  float qt = distFromCenter * distFromCenter;
                  float mt = distFromCenter; 

                  float angle = qt * uSpin * (2.0 - sqrt(1.0 - qt));
                  float branchOffset = (PI2 / uBranches) * floor(seed.x * uBranches);
                  
                  p.x = distFromCenter * cos(angle + branchOffset) * uRadius;
                  p.z = distFromCenter * sin(angle + branchOffset) * uRadius;

                  float scatterMultiplier = mix(0.15, 0.65, mt); 
                  p += scatter(seed) * random(seed.zx) * uRandomness * scatterMultiplier;
                  
                  p.y *= 0.2 + mt * 0.15;

                  vec3 temp = p;
                  float ac = cos(-uTime * 0.2);
                  float as = sin(-uTime * 0.2);
                  p.x = temp.x * ac - temp.z * as;
                  p.z = temp.x * as + temp.z * ac;

                  vDistance = mt;

                  vec4 mvp = modelViewMatrix * vec4(p, 1.0);
                  gl_Position = projectionMatrix * mvp;
                  gl_PointSize = (10.0 * size * uSize) / -mvp.z;
                }
              `,
              fragmentShader: `
                precision highp float;
                uniform vec3 uColorInn;
                uniform vec3 uColorOut;
                uniform sampler2D uAlphaMap;
                varying float vDistance;
                #define PI  3.14159265359

                void main() {
                  vec2 uv = vec2(gl_PointCoord.x, 1.0 - gl_PointCoord.y);
                  float a = texture2D(uAlphaMap, uv).g;
                  if (a < 0.1) discard;

                  vec3 color = mix(uColorInn, uColorOut, vDistance);
                  
                  float coreWhite = smoothstep(0.06, 0.0, vDistance);
                  color = mix(color, vec3(1.0, 1.0, 0.9), coreWhite * 0.25); 

                  float c = step(0.99, (sin(gl_PointCoord.x * PI) + sin(gl_PointCoord.y * PI)) * 0.5);
                  color = mix(color, color * 1.5, c); 

                  gl_FragColor = vec4(color * 0.75, a * 0.55);
                }
              `,
              transparent: true,
              depthTest: false,
              depthWrite: false,
              blending: THREE.AdditiveBlending,
            });

            const galaxy = new THREE.Points(galaxyGeometry, galaxyMaterial);
            galaxy.material.onBeforeCompile = (shader) => {
              shader.vertexShader = shader.vertexShader.replace("#include <random, scatter>", shaderUtils);
            };

            galaxyGroup = new THREE.Group();
            galaxyGroup.add(galaxy);
            
            galaxyGroup.position.set(70, -50, -55); 
            galaxyGroup.scale.set(10, 10, 10); 
            
            galaxyGroup.rotation.x = Math.PI / 3.0; 
            galaxyGroup.rotation.z = -Math.PI / 12; 

            scene.add(galaxyGroup);

            document.addEventListener('mousemove', onDocumentMouseMove, false);
            window.addEventListener('resize', onWindowResize, false);

            animate3D();
        }

        function onWindowResize() {
            windowHalfX = window.innerWidth / 2;
            windowHalfY = window.innerHeight / 2;
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        }

        function onDocumentMouseMove(event) {
            mouseX = (event.clientX - windowHalfX);
            mouseY = (event.clientY - windowHalfY);
        }

        function animate3D() {
            requestAnimationFrame(animate3D);

            if(dustParticles) {
                dustParticles.rotation.y -= 0.0002;
                dustParticles.rotation.x += 0.0001;
            }
            if(starParticles) {
                starParticles.rotation.y += 0.0001;
            }

            if(galaxyMaterial) {
                galaxyMaterial.uniforms.uTime.value += 0.00015; 
            }
            if(galaxyGroup) {
                galaxyGroup.rotateY(0.0002); 
            }

            camera.position.x += (mouseX * 0.008 - camera.position.x) * 0.05;
            camera.position.y += (-mouseY * 0.008 - camera.position.y) * 0.05;
            camera.lookAt(scene.position);
            renderer.render(scene, camera);

            const asciiTarget = document.getElementById('ascii-art-target');
            if (asciiTarget) {
                const moveX = -(mouseX * 0.03);
                const moveY = -(mouseY * 0.03);
                asciiTarget.style.transform = `translate3d(${moveX}px, ${moveY}px, 0)`;
            }
        }

        const fallbackFrames = [
          [
            "                                                        ",
            "                                                        ",
            "                          <c>+++==*%%%%%%%%%%%%*==+++</c>                          ",
            "                      <c>++****++</c>                <c>++****++</c>                      ",
            "                  <c>++**++</c>                            <c>++**++</c>                  ",
            "              <c>xx**+=</c>        o+*%$@@@@@@$%*+o          <c>=+**xx</c>              ",
            "            <c>xx**oo</c>      ·=$@@@@@@@$$$$$$$$@@@@@@@$=·      <c>oo**xx</c>            ",
            "          <c>xx**</c>       x$@@@$$$$$$$$$$$$$$$$$$$$$$$$@@@$x       <c>**xx</c>          ",
            "        <c>ox**</c>      ·$@@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@@$·      <c>**xo</c>        ",
            "        <c>==+~</c>    ~@@@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@@@~    <c>~+==</c>        ",
            "      <c>x+++</c>     $@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@$     <c>+++x</c>      ",
            "      <c>==</c>     ·@@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@@·     <c>==</c>      ",
            "    <c>ox++</c>    ~@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@~    <c>++xo</c>    ",
            "    <c>+++~</c>    @$$$$$@@@@@@@@@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@    <c>~+++</c>    ",
            "    <c>==</c>     $$$$$@@%%%%%%$$$$$$$@@@@@$$$@@@@@@@@@@@@@@@@@@@@$$$$$$     <c>==</c>    ",
            "    <c>==</c>     @$$$$* $$$$%                  =$$$$$@     <c>==</c>    ",
            "    <c>==</c>    ·$$$$@                  x@$@                    @$$$$$·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$%                ·$$$$%                  *$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$@@$%%$$$$$$@@@@@@@@$$$$@@@@@@@@@@@@@@@@@@@@$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$$$$@@@@@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ·@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@·    <c>==</c>    ",
            "    <c>==</c>    ~$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$~    <c>==</c>    ",
            "    <c>==</c>     @@$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$@@     <c>==</c>    ",
            "    <c>==x·</c>    $@@$$$$$$$$$@@@@@@@@@@$$$$$$$$@@@@@@@@@@$$$$$$$$$@@$    <c>·+==</c>    ",
            "    <c>++++</c>      =@@@@@@@@@* x$@@@@@@@@$x       *@@@@@@@@@=      <c>++++</c>    ",
            "    <c>xx==++</c>                                                        <c>++==oo</c>    ",
            "      <c>++===+</c>              <c>++%%+o</c>            <c>o+%%++</c>              <c>+===++</c>      ",
            "        <c>++=====%+=++++*=*========***++++***========*=*++++=+%=====++</c>        ",
            "          <c>xx++==******====++</c>  <c>++==********==++</c>  <c>++====******==++xx</c>          ",
            "                <c>++++</c>              <c>++++</c>              <c>++++</c>                  ",
            "                                                                        "
          ]
        ];

        let animationFrames = fallbackFrames;
        let currentFrameIndex = 0;

        async function initGhostAnimation() {
            const targetElement = document.getElementById('ascii-art-target');
            
            try {
                const res = await fetch('https://raw.githubusercontent.com/SohelIslamImran/ghosttime/main/src/animation-data.ts');
                const text = await res.text();
                
                let jsonString = text.replace(/export const ANIMATION_DATA(\s*:\s*string\[\]\[\])?\s*=\s*/, '').trim();
                if (jsonString.endsWith(';')) jsonString = jsonString.slice(0, -1);
                jsonString = jsonString.replace(/,(\s*[\]}])/g, '$1');
                
                const parsedData = JSON.parse(jsonString);
                if (parsedData && parsedData.length > 0) {
                    animationFrames = parsedData;
                }
            } catch (error) {
                console.error("No se pudo cargar la animación completa, usando fallback:", error);
            }

            targetElement.style.opacity = '1';

            setInterval(() => {
                currentFrameIndex = (currentFrameIndex + 1) % animationFrames.length;
                renderCurrentFrame();
            }, 30);
        }

        function renderCurrentFrame() {
            const asciiTarget = document.getElementById('ascii-art-target');
            const frameData = animationFrames[currentFrameIndex];
            
            let htmlContent = '';
            for (let i = 0; i < frameData.length; i++) {
                const line = frameData[i];
                const parts = line.split(/(<c>|<\/c>)/g);
                let isColored = false;
                let lineHtml = '';
                
                for (let part of parts) {
                    if (part === '<c>') {
                        isColored = true;
                    } else if (part === '</c>') {
                        isColored = false;
                    } else if (part) {
                        const safePart = part.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        if (isColored) {
                            lineHtml += `<span style="color: #0ff; text-shadow: 0 0 10px rgba(0, 255, 255, 0.7)">${safePart}</span>`;
                        } else {
                            lineHtml += `<span style="color: #e5e7eb">${safePart}</span>`;
                        }
                    }
                }
                htmlContent += lineHtml + '\n';
            }
            asciiTarget.innerHTML = htmlContent;
        }

        if ('speechSynthesis' in window) {
            window.speechSynthesis.onvoiceschanged = () => {
                vocesDisponibles = window.speechSynthesis.getVoices();
            };
        }

        function hablarTexto(texto) {
            if ('speechSynthesis' in window) {
                let textoLimpio = texto.replace(/[\*\[\]\>_]/g, '');
                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(textoLimpio);
                utterance.lang = 'es-ES';
                
                if (nombreVozDeseada !== "") {
                    const vozSeleccionada = vocesDisponibles.find(v => v.name.includes(nombreVozDeseada));
                    if (vozSeleccionada) utterance.voice = vozSeleccionada;
                }
                
                window.speechSynthesis.speak(utterance);
            }
        }

        window.onload = function() {
            init3D(); 
            initGhostAnimation();
            chatInput.focus();

            setTimeout(async () => {
                const elementoIA = agregarMensaje('', 'ai');
                await tipearTexto(elementoIA, "Hola, ¿en qué puedo ayudarte?");
            }, 500);
        };

        function handleKeyPress(e) {
            if (e.key === 'Enter') enviarMensaje();
        }

        function scrollToBottom() {
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }

        function agregarMensaje(texto, emisor) {
            const msgDiv = document.createElement('div');
            msgDiv.className = `message msg-${emisor}`;
            
            if (emisor === 'user') {
                msgDiv.textContent = `Usuario: ${texto}`;
                chatHistory.appendChild(msgDiv);
                scrollToBottom();
            } else if (emisor === 'system') {
                msgDiv.textContent = `> ${texto}`;
                chatHistory.appendChild(msgDiv);
                scrollToBottom();
            } else {
                msgDiv.textContent = 'IA: ';
                chatHistory.appendChild(msgDiv);
                return msgDiv;
            }
        }

        async function tipearTexto(elemento, texto, velocidad = 15) {
            isSpeaking = true;
            elemento.classList.add('cursor');
            
            for (let i = 0; i < texto.length; i++) {
                elemento.textContent += texto.charAt(i);
                scrollToBottom();
                const delay = velocidad + (Math.random() * 10 - 5);
                await new Promise(r => setTimeout(r, delay));
            }
            
            elemento.classList.remove('cursor');
            isSpeaking = false;
        }

        async function enviarMensaje() {
            const texto = chatInput.value.trim();
            if (!texto || isWaitingResponse || isSpeaking) return;

            chatInput.value = '';
            chatInput.disabled = true;
            btnSend.disabled = true;
            
            agregarMensaje(texto, 'user');
            isWaitingResponse = true;

            try {
                // MODELO ACTUALIZADO AQUÍ
                const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=${apiKey}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        contents: [{ parts: [{ text: texto }] }]
                    })
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    const errorMessage = errorData.error?.message || `Código de estado: ${response.status}`;
                    throw new Error(`Error de conexión (${errorMessage})`);
                }

                const data = await response.json();
                const textoIA = data.candidates?.[0]?.content?.parts?.[0]?.text || "Error en la matriz de datos.";

                isWaitingResponse = false;
                const elementoIA = agregarMensaje('', 'ai');
                
                hablarTexto(textoIA);
                await tipearTexto(elementoIA, textoIA);

            } catch (error) {
                isWaitingResponse = false;
                agregarMensaje(`ERROR DE SISTEMA: ${error.message}`, 'system');
            } finally {
                chatInput.disabled = false;
                btnSend.disabled = false;
                chatInput.focus();
            }
        }
    </script>
</body>
</html>