<template>
    <h6 id="xp-gained">25</h6>
    <div ref="container" style="width: 100vw; height: 100vh;"></div>
</template>


<script setup>
import { onMounted, ref } from 'vue'
import * as THREE from 'three'
import { OrbitControls } from 'three/addons/controls/OrbitControls.js'
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js'
import { Vector2, Raycaster } from 'three'

import Stats from 'stats.js'

import { createCharacter } from '@/utils/mining-game/character'
import { createMap } from '@/utils/mining-game/mapBuilder'
// should be lower case???
import { CharacterController } from '@/utils/mining-game/characterController'
import { MiningManager } from '@/utils/mining-game/miningManager'
import { set } from '@vueuse/core'


const container = ref(null)
const scene = new THREE.Scene()
const loader = new THREE.TextureLoader()
const gltfLoader = new GLTFLoader()
const clock = new THREE.Clock()

const mouse = new Vector2()
const raycaster = new Raycaster()

let characterModel = null // Store reference to character

let camera, renderer, controls, mixer, characterController, miningManager

const cameraHeight = 2;

let stats;

let isOrbiting = false;

let hoveringRock = false;

let originalColour = null; // To store the original color of the rock

let lastRock = null; // To keep track of the last rock hovered over

let xpGained = null;;

onMounted(() => {

    init()

    // Add stats panel
    stats = new Stats()
    stats.showPanel(0) // 0: fps, 1: ms, 2: mb
    document.body.appendChild(stats.dom)

    // Handle resize
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight
        camera.updateProjectionMatrix()
        renderer.setSize(window.innerWidth, window.innerHeight)
    })

    createMap(scene);


    // Controls


    renderer.domElement.addEventListener('pointerdown', () => {
        setTimeout(() => {
            isOrbiting = true; // The user started interacting with orbit controls
            characterController.setOrbitingState(isOrbiting);  // Update the controller
        }, 200);
    });

    renderer.domElement.addEventListener('pointerup', () => {
        setTimeout(() => {
            isOrbiting = false;  // Orbiting ends after a short delay
            characterController.setOrbitingState(isOrbiting);  // Update the controller

        }, 200)
    });


    renderer.domElement.addEventListener('mousemove', onMouseMove);




    characterController = new CharacterController(camera, scene, renderer)
    miningManager = new MiningManager()
    characterController.setMiningManager(miningManager); // Pass mining manager to character controller
    miningManager.setCharacterController(characterController); // Pass character controller to mining manager


    // Ensure mixer is passed after character is loaded
    createCharacter(scene).then(({ mixer, model, actions }) => {
        // Check if actions is correctly passed
        if (actions && actions.idleAction && actions.walkAction) {
            // console.log("Actions:", actions); // Debug log to check if actions are available
            characterModel = model;
            characterController.setCharacter(characterModel, actions); // Pass actions (idleAction, walkAction) to controller
            // Now that the mixer is ready, start the animation loop
            startAnimationLoop(mixer); // Start the animation loop after mixer is ready
        } else {
            console.error("Actions are undefined or missing idleAction or walkAction.");
        }
    }).catch((error) => {
        console.error('Error loading character:', error);
    });

    xpGained = document.getElementById('xp-gained');



})

// Create Scene

function init() {
    // Scene 

    scene.background = new THREE.Color(0x87ceeb); // Light blue sky
    //     const skyTexture = loader.load('/storage/games/mining/textures/background/sky.jpg', () => {
    //     skyTexture.colorSpace = THREE.SRGBColorSpace
    //     scene.background = skyTexture
    // })
    // const skyTexture = new THREE.TextureLoader().load('storage/games/mining/textures/background/sky.jpg', () => {
    //     skyTexture.colorSpace = THREE.SRGBColorSpace; // Ensure correct color space
    //     skyTexture.minFilter = THREE.LinearFilter; // Disable mipmaps for sharper texture
    //     skyTexture.magFilter = THREE.LinearFilter; // Linear filtering for sharper results
    // });

    // // Create a sphere to act as the skybox
    // const skyGeometry = new THREE.SphereGeometry(500, 60, 40);
    // const skyMaterial = new THREE.MeshBasicMaterial({
    //     map: skyTexture,
    //     side: THREE.BackSide
    // });
    // const sky = new THREE.Mesh(skyGeometry, skyMaterial);
    // scene.add(sky);
    scene.fog = new THREE.Fog(0x87ceeb, 10, 20);


    // Camera

    camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000)
    camera.position.set(0, 5, 12)
    camera.lookAt(0, 0, 0)

    // Renderer

    renderer = new THREE.WebGLRenderer({ antialias: true })
    renderer.setSize(window.innerWidth, window.innerHeight)
    container.value.appendChild(renderer.domElement)

    // Controls

    controls = new OrbitControls(camera, renderer.domElement)
    controls.minDistance = 5;   // minimum zoom-in distance
    controls.maxDistance = 10;  // maximum zoom-out distance ✅
    // controls.minPolarAngle = Math.PI / 4        // 45° above horizontal
    // controls.maxPolarAngle = Math.PI / 2        // 90° (straight down)
    controls.update()

    // Lighting
    scene.add(new THREE.AmbientLight(0xffffff, 0.6))

    const directionalLight = new THREE.DirectionalLight(0xffffff, 1)
    directionalLight.position.set(10, 20, 10)
    scene.add(directionalLight)

    // Animate

    function animate() {
        stats.begin();
        const maxPan = 5
        const delta = clock.getDelta()


        // Limit dragging (panning) by clamping the target
        // controls.target.x = THREE.MathUtils.clamp(controls.target.x, -maxPan, maxPan)
        // controls.target.z = THREE.MathUtils.clamp(controls.target.z, -maxPan, maxPan)

        // Optionally clamp camera position too if needed
        // // Clamp camera position
        // camera.position.x = THREE.MathUtils.clamp(camera.position.x, -maxPan * 2, maxPan * 2)
        // camera.position.z = THREE.MathUtils.clamp(camera.position.z, -maxPan * 2, maxPan * 2)


        if (mixer) {
            mixer.update(delta);  // Update animations
        } else {
        }
        controls.update()

        renderer.render(scene, camera)
        stats.end();
    }

    renderer.setAnimationLoop(animate)
}

function startAnimationLoop(mixer) {
    renderer.setAnimationLoop(function animate() {
        stats.begin();
        const delta = clock.getDelta();

        if (characterModel) {
            // Update camera position based on the character's position
            // const offset = new THREE.Vector3(0, 10, 0); // Camera offset behind and above the character
            // camera.position.copy(characterModel.position).add(offset);  // Set the camera's position
            // camera.rotation.y = characterModel.rotation.y - Math.PI; // Correct the camera rotation direction        // Make the camera always look at the character
            // camera.rotation.x = characterModel.rotation.x - Math.PI;

        }
        controls.update();

        // Update the mixer if it's available
        if (mixer) {
            mixer.update(delta); // Update the animation mixer with the delta time
            camera.position.y = Math.max(camera.position.y, 1.5) // ✅ no going underground

            // Set the camera position behind and above the character
            if (!isOrbiting) {

                // camera.position.y = cameraHeight;  // Camera 5 units above the character
                // camera.position.z = characterModel.position.z - 10; // Camera -10 behind the character
                // console.log('ORBITTTT')
            }
            // camera.position.x = characterModel.position.x - Math.PI; // Camera -10 behind the character

            // Maintain the camera's x position relative to the character's x position

            // Make the camera always look at the character
            // camera.lookAt(characterModel.position.x, camera.position.y, characterModel.position.z);
            controls.target.set(characterModel.position.x, cameraHeight, characterModel.position.z); // Set the target to the character    
            // console.log(camera.p)

        } else {
            console.log('Mixer is not available yet');
        }


        renderer.render(scene, camera);
        stats.end();
    });
}

function onMouseMove(event) {
    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);
    //   const intersects = raycaster.intersectObjects(objects);


    // Raycasting

    // this.raycaster.setFromCamera(this.mouse, this.camera)
    const intersects = raycaster.intersectObjects(scene.children, true)

    if (intersects.length > 0) {
        const target = intersects[0].object

        // Check if target is floor or ore
        const name = target.name.toLowerCase()



        if (name.includes('rock') && target.userData.health > 0 && !hoveringRock) {

            xpGained.style.left = mouse.x + "px";
            console.log('MLSE X', mouse.x)
            xpGained.style.top = mouse.y + "px";
            xpGained.style.fontSize = "80px";


            // Hovered: change color
            originalColour = target.material.color.clone();
            target.material.color.set(0xffd700);
            lastRock = target;

            hoveringRock = true; // Set hoveringRock to true when hovering over a rock
        }

        if (hoveringRock && lastRock.userData.id !== target.userData.id) {
            lastRock.material.color.set(originalColour);
            console.log('aaaaaaaaaaaaa')
            lastRock = null;
            hoveringRock = false; // Reset hoveringRock when not hovering over a rock
        }
    }

}



</script>


<style scoped>
/* .three-container {
        width: 100%;
        height: 100%;
        overflow: hidden;
        position: relative;
    } */
#xp-gained {
    position: absolute;
    /* allows free positioning */
    left: 500px;
    top: 50px;
    font-size: 20px;
    color: blue;
    transition: all 0.2s ease;
    /* smooth movement */
}
</style>
