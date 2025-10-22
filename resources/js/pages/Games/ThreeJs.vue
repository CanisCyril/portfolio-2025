<template>
    <!-- Container for Three.js renderer -->
    <div ref="container" class="three-container aspect-square max-w-[600px] w-full mx-auto"></div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import * as THREE from 'three'

import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader'


// Utils import
import { createCamera } from '@/utils/mining-game/camera'
import { createControls } from '@/utils/mining-game/controls'
import { createLighting } from '@/utils/mining-game/lighting'
import { createScene } from '@/utils/mining-game/scene'
import { createRenderer } from '@/utils/mining-game/renderer'

const gltfLoader = new GLTFLoader()
let oreModel = null
let characterModel = null
let character = null


// gltfLoader.load('/storage/games/mining/ores/ore.glb', (gltf) => {
//   oreModel = gltf.scene
// })



// Vue DOM ref
const container = ref(null)

// Scene globals
let scene, camera, renderer, cube, animationId, controls

// Game state
let meshes = []     // Store mesh grid (voxel terrain)
let data = []       // Map data (tile metadata)
const size = 12    // Size of the map
// Raycaster
const raycaster = new THREE.Raycaster()
const mouse = new THREE.Vector2()
let selectedObject = undefined;
let selectedObjectColour = null
const selectableObjects = []

// Load texture
const textureLoader = new THREE.TextureLoader()
const floorTexture = textureLoader.load('/storage/games/mining/textures/grey_floor.png')
floorTexture.wrapS = floorTexture.wrapT = THREE.RepeatWrapping
floorTexture.repeat.set(1, 1)

// Character movement

let moveTarget = null
const moveSpeed = 0.01 // Adjust this for faster/slower movement


onMounted(() => {
    initScene()
    // createCube()
    // createMap(size)
    // initializeMeshesFromMap(data)

    gltfLoader.load('/storage/games/mining/ores/ore_vein.glb', (gltf) => {
        oreModel = gltf.scene
        initializeMeshesFromMap(data) // Only run this once model is loaded
    })


    // ✅ Load character model
    gltfLoader.load('/storage/games/mining/models/good_beard_man.glb', (gltf) => {
        characterModel = gltf.scene
        character = characterModel.clone()
        character.scale.set(0.01, 0.01, 0.01)
        character.position.set(5, 0.2, 5)
        scene.add(character)
    })

    start()
    window.addEventListener('resize', onResize)
    renderer.domElement.addEventListener('mousedown', onMouseDown)
    renderer.domElement.addEventListener('mouseup', onMouseUp)
})

onBeforeUnmount(() => {
    stop()
    window.removeEventListener('resize', onResize)
    renderer.domElement.removeEventListener('mousedown', onMouseDown)
    renderer.domElement.removeEventListener('mouseup', onMouseUp)
})

/**
 * Initializes scene, camera, renderer, and controls
 */
function initScene() {

    // Renderer
    renderer = createRenderer(container.value)

    // Create scene with optional fog
    scene = createScene({
        backgroundColor: 0x87CEEB,
        // fog: { color: 0x4d, near: 10, far: 30 } // Uncomment if you want fog
    })

    // Create camera with aspect ratio based on container size
    camera = createCamera({ aspect: container.value.clientWidth / container.value.clientHeight })

    // Camera Controls
    controls = createControls(camera, renderer.domElement)

    // Create Lighting
    createLighting(scene);

}


// function createMap(size) {
//     let oreCount = 0        // Track number of placed ores
//     const maxOres = 5       // Max ore limit
//     data = []
//     for (let x = 0; x < size; x++) {
//         const column = []
//         for (let y = 0; y < size; y++) {
//             const tile = {
//                 x,
//                 y,
//                 selectable: false,
//             }

//             //Check how many ores in total

//             // if(oreCount < maxOres) {
//             if (Math.random() < 0.02) { // 10% chance of ore
//                 tile.ore = 1; // Ore present
//                 tile.selectable = true; // Make it selectable
//                 oreCount++;
//             }
//             // }
//             column.push(tile)

//         }


//         data.push(column)
//     }
// }

// /**
//  * Converts tile data into visible block meshes
//  */
// function initializeMeshesFromMap(data) {

//     meshes = []
//     for (let x = 0; x < data.length; x++) {
//         const row = []
//         for (let y = 0; y < data[x].length; y++) {

//             // Floor Geometry
//             const geometry = new THREE.BoxGeometry(1, 1, 1)
//             const material = new THREE.MeshBasicMaterial({ map: floorTexture })
//             const mesh = new THREE.Mesh(geometry, material)
//             mesh.position.set(x, 0, y)

//             mesh.userData.tile = data[x][y] // Store tile metadata in mesh

//             scene.add(mesh)
//             row.push(mesh)

//             //   Ore Geometry
//             if (data[x][y].ore == 1 && oreModel) {
//                 const oreMesh = oreModel.clone()
//                 oreMesh.position.set(x, 0, y)
//                 oreMesh.scale.set(0.8, 1, 0.8) // Adjust scale to fit your tile
//                 oreMesh.position.set(x, 1, y) // Slightly above ground

//                 oreMesh.userData.tile = data[x][y]

//                 scene.add(oreMesh)
//                 row.push(oreMesh)

//                 oreMesh.traverse((child) => {
//                     if (child.isMesh) {
//                         child.material = child.material.clone()
//                         selectableObjects.push(child)
//                         child.userData.tile = data[x][y]         // Position reference
//                         child.userData.hp = 100                  // Starting HP
//                         child.userData.cooldown = 0              // Cooldown timer
//                         child.userData.parent = oreMesh          // (Optional) ref to top group
//                     }
//                 })

//             }
//         }
//         meshes.push(row)
//     }

// }

/**
 * Render loop
 */
function draw() {
    // Animate cube rotation
    if (character) {
        //   character.rotation.y += 0.01 // Optional spin

        if (moveTarget) {
            const direction = new THREE.Vector3().subVectors(moveTarget, character.position)
            const distance = direction.length()
            if (distance > 0.05) {
                direction.normalize()
                character.position.add(direction.multiplyScalar(moveSpeed))

                // Rotate character to face the direction
                const angle = Math.atan2(direction.x, direction.z)
                character.rotation.y = angle
            } else {
                character.position.copy(moveTarget)
                moveTarget = null
            }
        }
    }


    controls.update()
    renderer.render(scene, camera)
}

/**
 * Start animation
 */
function start() {
    if (!renderer) return // prevent crash
    renderer.setAnimationLoop(draw)
}

function stop() {
    if (!renderer) return // prevent crash
    renderer.setAnimationLoop(null)
}


/**
 * Responsive canvas
 */
function onResize() {
    const width = container.value.clientWidth
    const height = container.value.clientHeight
    camera.aspect = width / height
    camera.updateProjectionMatrix()
    renderer.setSize(width, height)
}

/**
 * Mouse event (currently logs only)
 */
function onMouseUp(event) {
    console.log('Mouse button released')
}

function onMouseDown(event) {

    const rect = renderer.domElement.getBoundingClientRect()
    mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1
    mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1


    raycaster.setFromCamera(mouse, camera)

    let intersects = raycaster.intersectObjects(selectableObjects, false);

    if (intersects.length > 0) {
        // Restore previous object's color
        if (selectedObject) selectedObject.material.color.set(selectedObjectColour)

        // Select new object
        selectedObject = intersects[0].object;

        console.log('Selected object:', selectedObject)
        selectedObjectColour = selectedObject.material.color.getHex() // ✅ safer numeric hex

        console.log('Intersected object:', intersects[0])
        console.log('Selected object:', selectedObject)

        // Highlight it red
        selectedObject.material.color.set(0xff0000)
        const tile = selectedObject.userData.tile
        if (tile) {
            moveCharacterTo(tile.x, tile.y)
        }
    } else {
        console.log('No selectable object intersected')
        if (selectedObject) selectedObject.material.color.set(selectedObjectColour) // Restore previous color
        selectedObject = null; // Clear selection


    }

}

function moveCharacterTo(x, y) {
    // You could animate later — just a direct move now
    moveTarget = new THREE.Vector3(x, 0.2, y + 0.6)
}
</script>

<style>
.three-container {
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
}

canvas {
    display: block;
    width: 100% !important;
    height: 100% !important;
}
</style>
