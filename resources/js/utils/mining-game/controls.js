import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls'

export function createControls(camera, domElement, target = [5, 0, 5]) {
    const controls = new OrbitControls(camera, domElement)

    controls.target.set(...target)
    controls.enableDamping = true
    controls.dampingFactor = 0.05
    controls.maxPolarAngle = Math.PI / 2.5
    controls.minDistance = 10
    controls.maxDistance = 30

    controls.update()
    return controls
}
