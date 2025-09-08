// utils/mining-game/scene.js
import * as THREE from 'three'

const worldWidth = 256;
const worldDepth = 256;


export function createScene(options = {}) {
    const {
        backgroundColor = 0x87CEEB, // Default: sky blue
        fog = null,                 // Optional: { color, near, far }
    } = options

    const scene = new THREE.Scene()
    const textureLoader = new THREE.TextureLoader()
    // add fog later and make light follow character if making user look for ore
    // scene.fog = new THREE.FogExp2(0x555555, 0.05)

    // Testing Wall
        const wallTexture = textureLoader.load('/storage/games/mining/textures/dark_floor.jpg')
    const axesHelper = new THREE.AxesHelper(5) // size = 5 units
    axesHelper.position.set(0, 2, 0) // Raise it slightly above floor if needed

    scene.add(axesHelper)
    const size = 20;
    const height = 5;
    const full = size
    const half = size / 2 - 0.5 // center of the floor
    // const wallMaterial = new THREE.MeshBasicMaterial({ color: 0x333333 })
    const material = new THREE.MeshStandardMaterial({ color: 0x333333 })
    const wallThickness = 0.2

    const wallGeometry = new THREE.BoxGeometry(full, height, wallThickness)
    const wall1 = new THREE.Mesh(wallGeometry, material )
    wall1.position.set(0, height / 2, 10)
    scene.add(wall1)

    const wall2 = wall1.clone();
    wall2.position.set(0, height / 2, -10)
    scene.add(wall2)

    const wall3 = wall1.clone();

    wall3.rotation.y = Math.PI / 2 // 90 degrees
    wall3.position.set(10, height / 2, 0)
    scene.add(wall3)

    // Set background color
    scene.background = new THREE.Color(backgroundColor)

    // NEW TEST for floor

    const data = generateHeight(worldWidth, worldDepth);

    const geometry = new THREE.PlaneGeometry(20, 20, worldWidth - 1, worldDepth - 1);
    geometry.rotateX(-Math.PI / 2);

    const vertices = geometry.attributes.position.array;

    for (let i = 0, j = 0, l = vertices.length; i < l; i++, j += 3) {

        vertices[j + 1] = data[i] * 10;

    }

    // const texture = new THREE.CanvasTexture(generateTexture(data, worldWidth, worldDepth));
    const texture = textureLoader.load('/storage/games/mining/textures/sandstone_floor.jpg')

    texture.wrapS = THREE.ClampToEdgeWrapping;
    texture.wrapT = THREE.ClampToEdgeWrapping;
    texture.repeat.set(1, 1)
    // texture.colorSpace = THREE.SRGBColorSpace;

    const mesh = new THREE.Mesh(geometry, new THREE.MeshBasicMaterial({ map: texture }));
    scene.add(mesh);

    const renderer = new THREE.WebGLRenderer();
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(window.innerWidth, window.innerHeight);
    // renderer.setAnimationLoop(animate);
    // container.appendChild(renderer.domElement);

    // controls = new FirstPersonControls(camera, renderer.domElement);
    // controls.movementSpeed = 150;
    // controls.lookSpeed = 0.1;

    // stats = new Stats();
    // container.appendChild(stats.dom);


    //

    // window.addEventListener('resize', onWindowResize);




    
    //   scene.fog = new ThreeFogExp2(0xefd1b5, 0.0025) // Default: light fog

    // Optional fog
    // if (fog) {
    //     scene.fog = new THREE.Fog(fog.color, fog.near, fog.far)
    // }

    return scene
}


function generateHeight(width, height) {

    let seed = Math.PI / 4;
    window.Math.random = function () {

        const x = Math.sin(seed++) * 10000;
        return x - Math.floor(x);

    };

    const size = width * height, data = new Uint8Array(size);
    // const perlin = new ImprovedNoise(), z = Math.random() * 100;

    let quality = 1;

    for (let j = 0; j < 4; j++) {

        for (let i = 0; i < size; i++) {

            const x = i % width, y = ~ ~(i / width);
            // data[i] += Math.abs(perlin.noise(x / quality, y / quality, z) * quality * 1.75);

        }

        quality = 5;

    }

    return data;

}

// function generateTexture(data, width, height) {

//     let context, image, imageData, shade;

//     const vector3 = new THREE.Vector3(0, 0, 0);

//     const sun = new THREE.Vector3(1, 1, 1);
//     sun.normalize();

//     const canvas = document.createElement('canvas');
//     canvas.width = width;
//     canvas.height = height;

//     context = canvas.getContext('2d');
//     context.fillStyle = '#000';
//     context.fillRect(0, 0, width, height);

//     image = context.getImageData(0, 0, canvas.width, canvas.height);
//     imageData = image.data;

//     for (let i = 0, j = 0, l = imageData.length; i < l; i += 4, j++) {

//         vector3.x = data[j - 2] - data[j + 2];
//         vector3.y = 2;
//         vector3.z = data[j - width * 2] - data[j + width * 2];
//         vector3.normalize();

//         shade = vector3.dot(sun);

//         imageData[i] = (96 + shade * 128) * (0.5 + data[j] * 0.007);
//         imageData[i + 1] = (32 + shade * 96) * (0.5 + data[j] * 0.007);
//         imageData[i + 2] = (shade * 96) * (0.5 + data[j] * 0.007);

//     }

//     context.putImageData(image, 0, 0);

//     // Scaled 4x

//     const canvasScaled = document.createElement('canvas');
//     canvasScaled.width = width * 4;
//     canvasScaled.height = height * 4;

//     context = canvasScaled.getContext('2d');
//     context.scale(4, 4);
//     context.drawImage(canvas, 0, 0);

//     image = context.getImageData(0, 0, canvasScaled.width, canvasScaled.height);
//     imageData = image.data;

//     for (let i = 0, l = imageData.length; i < l; i += 4) {

//         const v = ~ ~(Math.random() * 5);

//         imageData[i] += v;
//         imageData[i + 1] += v;
//         imageData[i + 2] += v;

//     }

//     context.putImageData(image, 0, 0);

//     return canvasScaled;

// }
