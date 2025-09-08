// utils/mining-game/renderer.js
import * as THREE from 'three'

export function createRenderer(container) {
  const renderer = new THREE.WebGLRenderer({ antialias: true })

  // Optional: better quality rendering
  renderer.outputEncoding = THREE.sRGBEncoding
  renderer.toneMapping = THREE.ACESFilmicToneMapping
  renderer.toneMappingExposure = 1

  // Shadow support (if needed later)
  // renderer.shadowMap.enabled = true
  // renderer.shadowMap.type = THREE.PCFSoftShadowMap

  // Set size based on container dimensions
  const width = container.clientWidth
  const height = container.clientHeight
  renderer.setSize(width, height)

  // Optional: consider pixel ratio scaling
  renderer.setPixelRatio(window.devicePixelRatio)

  container.appendChild(renderer.domElement)
  return renderer
}
