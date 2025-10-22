import * as THREE from 'three'
import gsap from 'gsap'

export class CharacterController {
    constructor(camera, scene, renderer) {
        this.camera = camera
        this.scene = scene
        this.renderer = renderer
        this.isOrbiting = false;

        this.character = null
        this.raycaster = new THREE.Raycaster()
        this.mouse = new THREE.Vector2()

        this.walking = false;
        this.mining = false;

        // Max range for character movement radius

        this.BOUNDARY_X_MIN = -19;
        this.BOUNDARY_X_MAX = 18;
        this.BOUNDARY_Z_MIN = -19;
        this.BOUNDARY_Z_MAX = 18;


        // Bind click handler
        this.onClick = this.onClick.bind(this)
        window.addEventListener('click', this.onClick)
    }

    setCharacter(model, actions) {
        this.character = model
        this.idleAction = actions.idleAction;
        this.walkAction = actions.walkAction;
        this.startMiningAction = actions.startMiningAction;
        this.loopMiningAction = actions.loopMiningAction;
        this.endMiningAction = actions.endMiningAction;
    }

    setMiningManager(manager) {
        this.miningManager = manager;
    }

    // Method to update the orbiting state
    setOrbitingState(isOrbiting) {
        this.isOrbiting = isOrbiting;
    }

    onClick(event) {
        if (!this.character || this.isOrbiting) return

        const rect = this.renderer.domElement.getBoundingClientRect()
        this.mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1
        this.mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1

        this.raycaster.setFromCamera(this.mouse, this.camera)
        const intersects = this.raycaster.intersectObjects(this.scene.children, true)

        if (intersects.length > 0) {
            const target = intersects[0].object

            // Check if target is floor or ore
            const name = target.name.toLowerCase()
            const customName = target.userData?.customName?.toLowerCase()
            const point = intersects[0].point
            const textureSrc = target.material?.map?.image?.src || ''

            if (name.includes('rock') && target.userData.health > 0) {
                // rock position (use object's world position)
                const rockPos = new THREE.Vector3();
                target.getWorldPosition(rockPos);

                // character current position
                const charPos = this.character.position.clone();

                // direction from rock to character
                const dir = new THREE.Vector3();
                dir.subVectors(charPos, rockPos).setY(0).normalize(); // flat direction on XZ plane

                const offsetDistance = 1; // distance away from rock to stop at
                const targetPos = rockPos.clone().add(dir.multiplyScalar(offsetDistance));

                // Move character to targetPos
                this.moveCharacterTo(targetPos, customName, target);

                // Highlight selected

                // Store original color in userData (once)
                // if (!target.userData.originalColor) {
                //     target.userData.originalColor = target.material.color.clone();
                // }

                // Highlight (bright yellowish tint)
                // target.material.color.copy(new THREE.Color(0xffff66));

                // After e.g. 0.5 sec, restore
                // setTimeout(() => {
                //     target.material.color.copy(target.userData.originalColor);
                // }, 2000);

            }

            if (
                name.includes('grass') ||
                textureSrc.includes('grass')
            ) {
                this.moveCharacterTo(point, customName ?? null)
            }
        }
    }

    moveCharacterTo(targetPos, customName, target) {
        if (!this.character) return

        // If the character is already moving, cancel the current movement
        gsap.killTweensOf(this.character.position);

        // Prevent the character from moving beyond the boundaries
        targetPos.x = Math.max(this.BOUNDARY_X_MIN, Math.min(this.BOUNDARY_X_MAX, targetPos.x));
        targetPos.z = Math.max(this.BOUNDARY_Z_MIN, Math.min(this.BOUNDARY_Z_MAX, targetPos.z));

        // Keep on ground level
        targetPos.y = 1

        // Face movement direction
        const direction = new THREE.Vector3()
        direction.subVectors(targetPos, this.character.position)
        const angle = Math.atan2(direction.x, direction.z)
        this.character.rotation.y = angle


        // Calculate consistent movement speed
        const distance = this.character.position.distanceTo(targetPos)
        const speed = 2 // units per second — tweak as needed
        const duration = distance / speed

        if (!this.mining) {

            this.switchToWalking();
            gsap.to(this.character.position, {
                x: targetPos.x,
                y: targetPos.y,
                z: targetPos.z,
                duration,
                ease: 'linear',
                onUpdate: () => {

                    // Check if the character has reached the target position
                    if (this.character.position.distanceTo(targetPos) < 0.03 && !customName) {  // Small threshold to check if the character is near the target
                        this.switchToIdle();  // Transition to idle animation
                        gsap.killTweensOf(this.character.position);

                    }

                    if (this.character.position.distanceTo(targetPos) < 0.03 && customName == 'rock') {
                        gsap.killTweensOf(this.character.position);
                        this.switchToStartMining();
                        this.miningManager.startMining(target)
                    }
                },
            });

        } else {
            this.switchToEndMining();
        }



    }

    switchToWalking() {
        if (!this.walking && !this.mining) {

            this.walking = true;
            this.idleAction.fadeOut(0.5); // Fade out idle animation
            this.walkAction.reset().play().fadeIn(0.5); // Fade in walking animation
        }
    }

    switchToIdle() {
        // if (this.walking) {
        this.walking = false;
        this.walkAction.stop(); // Fade out walk animation
        this.idleAction.reset().startAt(this.idleAction.getMixer().time + 3).play(); // Fade in idle animation
        // }
    }


    switchToStartMining() {
        if (!this.mining) {

            // Clear events to animation errors
            this.startMiningAction?.getMixer()?.removeEventListener('finished', this._onStartMiningFinished);

            this.mining = true;
            this.walking = false;
            this.walkAction.stop();

            // Stop any previous startMining or loopMining
            this.startMiningAction.stop();
            this.loopMiningAction.stop();

            this.startMiningAction.reset();
            this.startMiningAction.setLoop(THREE.LoopOnce);
            this.startMiningAction.clampWhenFinished = true;
            this.startMiningAction.play();

            // Remove old listener if any
            if (this._onStartMiningFinished) {
                console.warn('Removing old startMining listener');
                this.startMiningAction.getMixer().removeEventListener('finished', this._onStartMiningFinished);
            }

            // Create new listener
            this._onStartMiningFinished = (e) => {
                if (e.action === this.startMiningAction) {
                    this.startMiningAction.getMixer().removeEventListener('finished', this._onStartMiningFinished);
                    this._onStartMiningFinished = null;
                    this.switchToMiningLoop();
                }
            };
            this.startMiningAction.getMixer().addEventListener('finished', this._onStartMiningFinished);
        }
    }

    switchToMiningLoop() {
        // Ensure startMiningAction is fully stopped
        this.startMiningAction.reset().stop();
        this.loopMiningAction.reset().play();
    }


    switchToEndMining() {

        // Stop loop mining safely
        this.loopMiningAction.stop();
        this.loopMiningAction.reset();

        // Prepare end mining animation
        this.endMiningAction.stop();
        this.endMiningAction.reset();
        this.endMiningAction.setLoop(THREE.LoopOnce);
        this.endMiningAction.clampWhenFinished = true;
        this.endMiningAction.play();

        // Set mining to false only after endMiningAction finishes
        const onFinished = (e) => {
            if (e.action === this.endMiningAction) {
                this.endMiningAction.getMixer().removeEventListener('finished', onFinished);
                this.mining = false; // Now safe to allow movement again
                this.endMiningAction.stop();

                //  If mining, stop - stops hp from ticking down
                this.miningManager.stopMining();

                this.switchToIdle(); // Switch back to idle animation
            }
        };
        this.endMiningAction.getMixer().addEventListener('finished', onFinished);
    }

    dispose() {
        window.removeEventListener('click', this.onClick)
    }
}
