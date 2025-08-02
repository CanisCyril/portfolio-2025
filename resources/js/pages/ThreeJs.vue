<template>
  <!-- This div will contain the Three.js canvas -->
  <div ref="container" class="three-container"></div>
</template>

<script setup>
// Import Vue's Composition API
import { ref, onMounted, onBeforeUnmount } from 'vue'

// Import core Three.js functionality
import * as THREE from 'three'

// Create a Vue ref to access the container DOM element
const container = ref(null)

// Declare variables for Three.js components
let scene        // Holds all 3D objects
let camera       // Determines what is visible to the player
let renderer     // Renders the scene to the canvas
let cube         // The cube mesh we'll animate
let animationId  // Stores the animation frame ID for cleanup

// Called automatically when the Vue component is mounted
onMounted(() => {
  initScene()      // Set up scene, camera, renderer
  createCube()     // Create and add the cube to the scene
  start()          // Start the render loop
  window.addEventListener('resize', onResize) // Handle screen resizes
})

// Called automatically before the component is destroyed
onBeforeUnmount(() => {
  stop() // Stop rendering
  window.removeEventListener('resize', onResize) // Cleanup event listener
})

/**
 * Initializes the Three.js scene, camera, and renderer
 */
function initScene() {
  // Create a new scene and set the background color (sky blue)
  scene = new THREE.Scene()
  scene.background = new THREE.Color(0x87CEEB)

  // Set up the perspective camera
  // Parameters: FOV, aspect ratio, near/far clipping planes
  camera = new THREE.PerspectiveCamera(
    75,
    window.innerWidth / window.innerHeight,
    0.1,
    1000
  )

  // Move the camera back so we can see the objects
  camera.position.z = 5

  // Create the WebGL renderer
  renderer = new THREE.WebGLRenderer({ antialias: true })
  renderer.setSize(window.innerWidth, window.innerHeight) // Match screen size

  // Append the renderer's canvas to the container div
  container.value.appendChild(renderer.domElement)
}

/**
 * Creates a rotating green cube and adds it to the scene
 */
function createCube() {
  // Create a box (cube) geometry
  const geometry = new THREE.BoxGeometry()

  // Create a basic green material (doesn't require lights)
  const material = new THREE.MeshBasicMaterial({ color: 0x00ff00 })

  // Combine geometry + material into a mesh
  cube = new THREE.Mesh(geometry, material)

  // Add the cube to the scene
  scene.add(cube)
}

/**
 * The animation loop: rotates the cube and renders the scene
 */
function draw() {
  // Rotate the cube slightly each frame
  cube.rotation.x += 0.01
  cube.rotation.y += 0.01

  // Render the scene from the perspective of the camera
  renderer.render(scene, camera)
}

/**
 * Starts the animation/render loop
 */
function start() {
  // `setAnimationLoop` is like requestAnimationFrame but can support VR
  renderer.setAnimationLoop(draw)
}

/**
 * Stops the animation/render loop
 */
function stop() {
  renderer.setAnimationLoop(null)
}

/**
 * Handle window resizing to keep aspect ratio and canvas size correct
 */
function onResize() {
  camera.aspect = window.innerWidth / window.innerHeight // Update aspect
  camera.updateProjectionMatrix()                        // Apply change
  renderer.setSize(window.innerWidth, window.innerHeight) // Resize canvas
}
</script>

<style>
/* Style the container to take full screen */
.three-container {
  width: 100vw;
  height: 100vh;
  overflow: hidden;
}

/* Make sure canvas doesn’t overflow and fills screen */
canvas {
  display: block;
}
</style>
