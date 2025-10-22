
import * as THREE from 'three'


export function createLighting(scene) {
    
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.6)
    scene.add(ambientLight)

    const directionalLight = new THREE.DirectionalLight(0xffffff, 1)
    directionalLight.position.set(10, 15, 10)
    scene.add(directionalLight)
}