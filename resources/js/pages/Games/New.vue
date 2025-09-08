<template>
    <div ref="containerRef" class="three-js-container w-full h-full"></div>
</template>

<script setup>
import * as THREE from 'three';
import { onMounted, onBeforeUnmount, ref } from 'vue';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
const containerRef = ref(null);
let renderer, camera, scene, cube, animationId, orbitControls;
const PI = Math.PI;
const PI90 = Math.PI / 2;
const loader = new THREE.TextureLoader();
const raycaster = new THREE.Raycaster();
const mouse = new THREE.Vector2();


const controls = {

    key: [0, 0],
    ease: new THREE.Vector3(),
    position: new THREE.Vector3(),
    up: new THREE.Vector3(0, 1, 0),
    rotate: new THREE.Quaternion(),
    current: 'Idle',
    fadeDuration: 0.5,
    runVelocity: 5,
    walkVelocity: 1.8,
    rotateSpeed: 0.05,
    floorDecale: 0,

};

onMounted(() => {
    window.addEventListener('mousedown', onMouseDown);
    init();

});

onBeforeUnmount(() => {
    if (animationId) cancelAnimationFrame(animationId);
    if (renderer) renderer.dispose();
    window.removeEventListener('resize', onWindowResize);
    window.addEventListener('mousedown', onMouseDown);


});

function init() {
    const container = containerRef.value;
    if (!container) return;
    // Scene
    scene = new THREE.Scene();
    scene.background = new THREE.Color(0x87ceeb);
    scene.fog = new THREE.Fog(0x5e5d5d, 1, 50);


    const colorMap = loader.load('/storage/games/mining/textures/dark_cave/Rock037_1K-JPG_Color.jpg');
    const normalMap = loader.load('/storage/games/mining/textures/dark_cave/Rock037_1K-JPG_NormalGL.jpg');
    const roughnessMap = loader.load('/storage/games/mining/textures/dark_cave/Rock037_1K-JPG_Roughness.jpg');
    const aoMap = loader.load('/storage/games/mining/textures/dark_cave/Rock037_1K-JPG_AmbientOcclusion.jpg');
    const displacementMap = loader.load('/storage/games/mining/textures/dark_cave/Rock037_1K-JPG_Displacement.jpg');

    // Optional: tile textures
    const repeat = 4;
    [colorMap, normalMap, roughnessMap, aoMap, displacementMap].forEach(tex => {
        tex.wrapS = tex.wrapT = THREE.RepeatWrapping;
        tex.repeat.set(repeat, repeat);
    });

    // Geometry (with enough segments for displacement to work)
    const geometry = new THREE.SphereGeometry(20, 128, 128); // high segment count for smoothness

    // Material using all maps
    const material = new THREE.MeshStandardMaterial({
        map: colorMap,
        normalMap: normalMap,
        roughnessMap: roughnessMap,
        aoMap: aoMap,
        displacementMap: displacementMap,
        displacementScale: 0.15, // tweak for depth
        roughness: 1.0,
        metalness: 0.0,
        side: THREE.BackSide,
    });

    // Mesh
    const cave = new THREE.Mesh(geometry, material);
    cave.receiveShadow = true;
    scene.add(cave);


    // Camera
    camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 100);
    camera.position.set(0, 2, 5);
    camera.lookAt(0, 0, 0);

    // Light
    const dirLight = new THREE.DirectionalLight(0xffffff, 2);
    dirLight.position.set(-2, 5, -3);
    dirLight.castShadow = true;

    const shadowCam = dirLight.shadow.camera;
    shadowCam.top = shadowCam.right = 2;
    shadowCam.bottom = shadowCam.left = -2;
    shadowCam.near = 3;
    shadowCam.far = 8;
    dirLight.shadow.mapSize.set(1024, 1024);

    scene.add(dirLight);
    scene.add(dirLight.target);
    // Ambient light for base illumination
    // Ambient light for even illumination
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.3); // strong and white
    scene.add(ambientLight);

    // Optional: Hemisphere light to fake sky and ground bounce
    const hemiLight = new THREE.HemisphereLight(0xffffff, 0xcccccc, 1.0);
    hemiLight.position.set(0, 10, 0);
    scene.add(hemiLight);





    //   // Ground
    //   const groundGeo = new THREE.PlaneGeometry(10, 10);
    //   const groundMat = new THREE.ShadowMaterial({ opacity: 0.3 });
    //   const ground = new THREE.Mesh(groundGeo, groundMat);
    //   ground.rotation.x = -Math.PI / 2;
    //   ground.position.y = 0;
    //   ground.receiveShadow = true;
    //   scene.add(ground);

    // Cube
    const cubeGeo = new THREE.BoxGeometry();
    const cubeMat = new THREE.MeshStandardMaterial({ color: 0xff8844 });
    cube = new THREE.Mesh(cubeGeo, cubeMat);
    cube.position.y = 0.5;
    cube.castShadow = true;
    cube.receiveShadow = true;
    scene.add(cube);

    // Raycaster
    // raycaster.setFromCamera( pointer, camera );

	// calculate objects intersecting the picking ray
	// const intersects = raycaster.intersectObjects( scene.children );

    // console.log('Intersected objects:', intersects);

    // Renderer
    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 0.5;
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    container.appendChild(renderer.domElement);

    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 0.2;


    orbitControls = new OrbitControls(camera, renderer.domElement);
    orbitControls.target.set(0, 1, 0);
    orbitControls.enableDamping = true;
    orbitControls.enablePan = false;
    orbitControls.maxPolarAngle = PI90 - 0.05;
    orbitControls.minDistance = 2;   // Minimum zoom in
    orbitControls.maxDistance = 15;  // Maximum zoom out
    orbitControls.maxPolarAngle = Math.PI / 2 - 0.05; // Look up max
    orbitControls.minPolarAngle = Math.PI / 4; // Optional: 45° from above
    orbitControls.update();

    addFloor();


    // Resize handling
    window.addEventListener('resize', onWindowResize);
    //     orbitControls.addEventListener('change', () => {
    //   renderer.render(scene, camera);
    // });

    // renderer.render(scene, camera);
    // Start animation
    animate();
}

function animate() {
    animationId = requestAnimationFrame(animate); // 🔁 Recursively schedules next frame

    // followCamera(cube); // 👈 Camera follows cube each frame (if added)

    renderer.render(scene, camera); // 👈 Draw the current frame
}


function onWindowResize() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
}

function addFloor() {

    const size = 50;
    const repeat = 16;

    const maxAnisotropy = renderer.capabilities.getMaxAnisotropy();

    const floorT = new THREE.TextureLoader().load('/storage/games/mining/textures/sandstone_floor.jpg');
    floorT.colorSpace = THREE.SRGBColorSpace;
    floorT.repeat.set(repeat, repeat);
    floorT.wrapS = floorT.wrapT = THREE.RepeatWrapping;
    floorT.anisotropy = maxAnisotropy;

    const floorN = new THREE.TextureLoader().load('/storage/games/mining/textures/sandstone_floor.jpg');
    floorN.repeat.set(repeat, repeat);
    floorN.wrapS = floorN.wrapT = THREE.RepeatWrapping;
    floorN.anisotropy = maxAnisotropy;

    const mat = new THREE.MeshStandardMaterial({
        map: floorT, normalMap: floorN, normalScale: new THREE.Vector2(0.5, 0.5), color: 0xaaaaaa,
        emissive: 0x222222, depthWrite: false, roughness: 0.85
    });

    const g = new THREE.PlaneGeometry(size, size, 50, 50);
    g.rotateX(- PI90);

    const floor = new THREE.Mesh(g, mat);
    floor.receiveShadow = true;
    scene.add(floor);

    controls.floorDecale = (size / repeat) * 4;

    // const bulbGeometry = new THREE.SphereGeometry(0.05, 16, 8);
    // const bulbLight = new THREE.PointLight(0xffee88, 2, 500, 2);

    // const bulbMat = new THREE.MeshStandardMaterial({ emissive: 0xffffee, emissiveIntensity: 1, color: 0x000000 });
    // bulbLight.add(new THREE.Mesh(bulbGeometry, bulbMat));
    // bulbLight.position.set(1, 0.1, - 3);
    // bulbLight.castShadow = true;
    // floor.add(bulbLight);

}

function onMouseDown(event) {

    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = - (event.clientY / window.innerHeight) * 2 + 1;

    console.log('Pointer moved:', event);


    raycaster.setFromCamera(mouse, camera);

    const intersects = raycaster.intersectObjects(scene.children, true);
    console.log('Intersects:', intersects);
    const intersectedPoint = intersects[0].point;
    console.log('test', intersectedPoint)

    // calculate pointer position in normalized device coordinates
    // (-1 to +1) for both components


        cube.position.set(
        intersectedPoint.x,
        intersectedPoint.y + 0.5, // lift by half cube height
        intersectedPoint.z
    );

}

// function followCamera(target, offset = new THREE.Vector3(0, 2, -5), lerpFactor = 0.1) {
//     // Compute desired camera position
//     const desiredPosition = target.position.clone().add(offset);

//     // Lerp camera position for smooth follow
//     camera.position.lerp(desiredPosition, lerpFactor);

//     // Look at the target or slightly above
//     const lookAt = target.position.clone();
//     lookAt.y += 1; // adjust height if needed
//     camera.lookAt(lookAt);
// }


</script>

<style scoped>
.three-js-container {
    width: 100%;
    height: 100%;
    display: block;
    overflow: hidden;
}

html,
body,
#app {
    margin: 0;
    padding: 0;
    height: 100%;
}
</style>
