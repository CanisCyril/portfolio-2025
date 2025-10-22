import * as THREE from 'three'

export function createCamera({ fov = 75, aspect = 1, near = 0.1, far = 1000, position = [10, 15, 10], lookAt = [5, 0, 5] }) {
    const camera = new THREE.PerspectiveCamera(fov, aspect, near, far)
    camera.position.set(...position)
    camera.lookAt(new THREE.Vector3(...lookAt))
    return camera
}