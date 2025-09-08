import * as THREE from 'three'
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js'

const gltfLoader = new GLTFLoader();

export function createCharacter(scene) {
    return new Promise((resolve, reject) => {
        gltfLoader.load('/storage/games/mining/models/miner.glb', (gltf) => {
            const model = gltf.scene;

            model.scale.set(1, 1, 1);
            model.position.set(0, 1, 0);

            model.traverse((child) => {
                if (child.isMesh) {
                    child.castShadow = true;
                    child.receiveShadow = true;
                }
            });

            const mixer = new THREE.AnimationMixer(model);

            const idleAction = mixer.clipAction(gltf.animations[1]);
            const walkAction = mixer.clipAction(gltf.animations[2]);
            const startMiningAction = mixer.clipAction(gltf.animations[3]); 
            const loopMiningAction = mixer.clipAction(gltf.animations[4]); 
            const endMiningAction = mixer.clipAction(gltf.animations[5]);
            // console.log('end min', endMiningAction)

            if (gltf.animations && gltf.animations.length > 0) {
                // const idleAction = mixer.clipAction(gltf.animations[1]);
                idleAction.play();
            }


            scene.add(model);
            resolve({ mixer, model, actions: { idleAction, walkAction, startMiningAction, loopMiningAction, endMiningAction } });
            }, undefined, reject);
    });
}
