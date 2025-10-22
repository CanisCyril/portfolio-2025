import * as THREE from 'three'
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js'

const gltfLoader = new GLTFLoader();
const loader = new THREE.TextureLoader();

export function createMap(scene) {
    const dirtTexture = loader.load(
        '/storage/games/mining/textures/dirt_texture.png',
        texture => {
            texture.wrapS = THREE.RepeatWrapping;
            texture.wrapT = THREE.RepeatWrapping;
            texture.magFilter = THREE.NearestFilter; // pixelated like Minecraft
            texture.minFilter = THREE.NearestFilter;
        },
        undefined,
        err => console.error('Texture loading failed', err)
    );

    const material = new THREE.MeshStandardMaterial({ map: dirtTexture });

    const cubeSize = 1;   // each cube is 1x1x1
    const width = 20;     // X axis count
    const depth = 5;      // Z axis count
    const wallHeight = 5; // number of cubes stacked vertically for the wall

    // --- Floor ---
    for (let x = 0; x < width; x++) {
        for (let z = 0; z < depth; z++) {
            const geometry = new THREE.BoxGeometry(cubeSize, cubeSize, cubeSize);
            const cube = new THREE.Mesh(geometry, material);

            cube.position.set(
                x - width / 2 + cubeSize / 2,
                -cubeSize / 2, // top surface at y = 0
                z - depth / 2 + cubeSize / 2
            );

            cube.castShadow = true;
            cube.receiveShadow = true;

            scene.add(cube);
        }
    }

    // --- Wall at the "back" edge ---
    for (let x = 0; x < width; x++) {
        for (let y = 0; y < wallHeight; y++) {
            const geometry = new THREE.BoxGeometry(cubeSize, cubeSize, cubeSize);
            const cube = new THREE.Mesh(geometry, material);

            cube.position.set(
                x - width / 2 + cubeSize / 2,
                y + cubeSize / 2, // stack upwards from y=0
                depth / 2 - cubeSize / 2 // back edge in +Z
            );

            cube.castShadow = true;
            cube.receiveShadow = true;

            scene.add(cube);
        }
    }
}
