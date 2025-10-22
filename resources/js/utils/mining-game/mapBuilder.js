import * as THREE from 'three'
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js'

const gltfLoader = new GLTFLoader();
const loader = new THREE.TextureLoader()

export function createMap(scene) {



    // Textures
    const loadTextures = [
        new Promise((resolve, reject) => {
            const grassTexture = loader.load('/storage/games/mining/textures/grass_texture.png', resolve, undefined, reject);
            grassTexture.colorSpace = THREE.SRGBColorSpace;
            grassTexture.wrapS = grassTexture.wrapT = THREE.RepeatWrapping;
        }),
        new Promise((resolve, reject) => {
            const dirtTexture = loader.load('/storage/games/mining/textures/dirt_texture.png', resolve, undefined, reject);
            dirtTexture.colorSpace = THREE.SRGBColorSpace;
            dirtTexture.wrapS = dirtTexture.wrapT = THREE.RepeatWrapping;
        }),
    ];

    // Models
    let treeModel = null;
    let rockModel = null;
    let fenceModel = null;


    // Load tree model as a Promise
    const loadTreeModel = new Promise((resolve, reject) => {
        gltfLoader.load('/storage/games/mining/models/trees/low_poly_tree_1.glb', (gltf) => {
            treeModel = gltf.scene;
            treeModel.scale.set(0.009, 0.009, 0.009);  // Scale if necessary

            // Traverse the model to enable shadows
            treeModel.traverse((child) => {
                if (child.isMesh) {
                    child.castShadow = true;
                    child.receiveShadow = true;
                }
            });

            resolve(treeModel);  // Resolve with the tree model
        }, undefined, (error) => {
            reject('Error loading tree model: ' + error);
        });
    });

    // Load rock model as a Promise
    const loadRockModel = new Promise((resolve, reject) => {
        gltfLoader.load('/storage/games/mining/models/rock.glb', (gltf) => {
            rockModel = gltf.scene;
            rockModel.scale.set(1.2, 1.2, 1.2);  // Scale if necessary

            // Traverse the model to enable shadows
            rockModel.traverse((child) => {
                if (child.isMesh) {
                    child.castShadow = true;
                    child.receiveShadow = true;
                }
            });

            resolve(rockModel);  // Resolve with the rock model
        }, undefined, (error) => {
            reject('Error loading rock model: ' + error);
        });
    });

    // Load rock model as a Promise
    const loadFenceModel = new Promise((resolve, reject) => {
        gltfLoader.load('/storage/games/mining/models/fences/low_poly_fence.glb', (gltf) => {
            fenceModel = gltf.scene;
            fenceModel.scale.set(0.009, 0.009, 0.009);  // Scale if necessary


            // Traverse the model to enable shadows
            fenceModel.traverse((child) => {
                if (child.isMesh) {
                    child.castShadow = true;
                    child.receiveShadow = true;
                }
            });

            resolve(fenceModel);  // Resolve with the rock model
        }, undefined, (error) => {
            reject('Error loading rock model: ' + error);
        });
    });

    

    const size = 40;
    const dirtHeight = 0.9;
    const grassHeight = 0.1;
    const oreHeight = 1.5;


    // Define the bounds for the 15x15 ore spawning area
    const centerSize = 40;
    const centerOffset = Math.floor(size / 2) - Math.floor(centerSize / 2);

    // Use Promise.all to wait for both models and textures to load
    Promise.all([loadTreeModel, loadRockModel, loadFenceModel, ...loadTextures]).then(([
        loadedTreeModel,
        loadedRockModel,
        loadedFenceModel,
        grassTexture,
        dirtTexture
    ]) => {

        // Materials
        const grassMaterial = new THREE.MeshStandardMaterial({ map: grassTexture });
        const dirtMaterial  = new THREE.MeshStandardMaterial({ map: dirtTexture }); 

        for (let i = 0; i < size; i++) {
            for (let j = 0; j < size; j++) {
                const offset = size / 2;

                // Dirt layer (under grass) — NEW
                const dirtGeo = new THREE.BoxGeometry(1.01, dirtHeight, 1.01);
                const dirt = new THREE.Mesh(dirtGeo, dirtMaterial);
                dirt.position.set(i - offset, dirtHeight / 2, j - offset); // centered base
                dirt.receiveShadow = true;
                scene.add(dirt);

                // Grass layer
                const grassGeo = new THREE.BoxGeometry(1.01, grassHeight, 1.01);
                const grass = new THREE.Mesh(grassGeo, grassMaterial);
                grass.position.set(i - offset, dirtHeight + grassHeight / 2, j - offset); // ✅ centered
                grass.castShadow = true;
                grass.receiveShadow = true;
                scene.add(grass);

                // Spawn Rock (ore) only in the center 15x15 area
                if (Math.random() < 0.009 && i >= centerOffset && i < centerOffset + centerSize && j >= centerOffset && j < centerOffset + centerSize) {
                    const rock = loadedRockModel.clone();  // Clone the rock model
                    // Random scale between 1.0 and 2.0 for X, Y, and Z axes

                    const randomScale = 1 + Math.random() * 1.5;  // Generates a random value between 1.0 and 2.0

                    // Apply the random scale to the rock model
                    rock.scale.set(randomScale, randomScale, randomScale);

                    // Random rotation between 0 and 2 * Math.PI radians (360 degrees) for each axis
                    // Random rotation on Y-axis between 0 and 2 * Math.PI radians (360 degrees)
                    const randomRotationY = Math.random() * 2 * Math.PI; // Random rotation for Y axis (left-right)

                    // Apply the random Y-axis rotation to the rock model
                    rock.rotation.set(0, randomRotationY, 0); // X and Z rotations remain 0, only Y is randomized

                    rock.position.set(i - offset, 1, j - offset);


                    // Data 

                    rock.traverse((child) => {
                        if (child.isMesh) {
                            child.material = child.material.clone();
                            child.userData = child.userData || {};
                            child.userData.id = j;
                            child.userData.customName = 'rock';
                            child.userData.health = 100;
                        }
                    }); 

                    scene.add(rock);
                }

                // Spawn Tree
                if (Math.random() < 0.005) {
                    const tree = loadedTreeModel.clone();  // Clone the tree model
                    tree.position.set(i - offset, 1, j - offset);
                    scene.add(tree);
                }

                // Add fence at all edges of the map
                if (i === 0 || i === size - 1 || j === 0 || j === size - 1) {
                    const fence = loadedFenceModel.clone();
                    fence.position.set(i - offset, 1, j - offset);

                    // Rotate fence on the left and right edges (i.e., when i === 0 or i === size - 1)
                    if (i === 0 || i === size - 1) {
                        fence.rotation.y = Math.PI / 2;  // Rotate 90 degrees around Y-axis
                    }

                    scene.add(fence);
                }

            }
        }
    }).catch((error) => {
        console.error('Error loading assets:', error);
    });
}
