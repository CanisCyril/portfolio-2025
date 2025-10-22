export class MiningManager {
    constructor() {
        this.miningInterval = null;
    }

    setCharacterController(controller) {
        this.characterController = controller;
    }

    startMining(target) {
        target.userData.healthBelow50 = false;
        console.log('Mining started on:', target);

        // Just in case mining was already running
        this.stopMining();

        this.miningInterval = setInterval(() => {
            target.userData.health -= 10;
            console.log('Rock HP:', target.userData.health);

             if (target.userData.health <= 50 && !target.userData.healthBelow50) {
                target.userData.healthBelow50 = true;
                target.material.color.set(0xFFA726).multiplyScalar(0.9); // muted amber/orange
            }

            if (target.userData.health <= 0) {
                console.log('Rock destroyed!');
                target.material.color.set(0xCC3333).multiplyScalar(0.8); // dark muted red

                this.stopMining();
            }
        }, 1600);
    }

    stopMining() {
        if (this.miningInterval) {
            clearInterval(this.miningInterval);
            this.characterController.switchToEndMining(); // Switch character to end mining animation
            this.miningInterval = null;
        }
    }
}
