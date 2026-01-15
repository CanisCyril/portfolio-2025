<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ProjectCard from '@/components/custom/ProjectCard.vue';

import {
    MoonIcon, SunIcon
} from '@heroicons/vue/24/outline'

import { ref, onMounted } from 'vue'

const STORAGE_KEY = 'theme' // 'light' | 'dark'

const isDark = ref(false)
const skills = ['Laravel', 'VueJs', 'PHP', 'JavaScript', 'HTML', 'CSS', 'Tailwind', 'NodeJs', 'Git', 'MySQL', 'AWS', 'Server Management']

function applyTheme(dark) {
    isDark.value = dark
    const el = document.documentElement
    el.classList.toggle('dark', dark)
    el.classList.add('theme-transition')
    localStorage.setItem(STORAGE_KEY, dark ? 'dark' : 'light')
}

function toggleTheme() {
    applyTheme(!isDark.value)
}



onMounted(() => {
    // load saved preference or default to light
    const saved = localStorage.getItem(STORAGE_KEY)
    setTimeout(() => {
        applyTheme(saved ? saved === 'dark' : false)

    }, 300)
})

</script>

<template>

    <Head>
        <title>Portfolio Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </Head>

    <nav class="min-h-[48px] flex items-center justify-between pl-2 border-b-2 dark:bg-slate-800 fade-in-nav">
        <div class="flex font-bold gap-2">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                </svg>
            </span>
            <span
                class="opacity-0 animate-[fadeBlurIn_0.6s_ease-out_forwards] [animation-delay:0.5s]">canis.portfolio</span>
        </div>
        <div class="flex gap-2 font-medium">
            <div class="hidden md:flex items-center gap-4 text-dark [&>a]:opacity-0 
            [&>a]:animate-[fadeBlurIn_0.8s_ease-out_forwards] 
            [&>a]:[animation-delay:0.5s] 
            [&>a:hover]:opacity-80">
                <!-- <div class="hidden md:flex items-center gap-4 text-dark [&>a:hover]:opacity-80"> -->
                <a href="#projects">Projects</a>
                <a href="#experience">Experience</a>
                <a href="#skills">Skills</a>
                <a href="#contact">Contact</a>
            </div>
            <div
                class="px-1 flex flex-row items-center opacity-0 animate-[fadeBlurIn_0.6s_ease-out_forwards] [animation-delay:0.5s]">
                <div class="px-2 hidden md:flex">
                    <button class="hover:opacity-80 border-x-2 px-4 dark:border-gray-600 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span>
                            CV
                        </span>
                    </button>
                </div>
                <div v-if="!isDark" class="cursor-pointer tooltip tooltip-right" data-tip="Dark Mode"
                    aria-label="Dark Mode" @click="toggleTheme">
                    <MoonIcon class="size-6 
                        [&>path]:transition-[fill,stroke] [&>path]:duration-500 [&>path]:ease-out
                        [&>path]:fill-transparent
                        hover:[&>path]:fill-black hover:[&>path]:stroke-black" />
                </div>
                <div v-if="isDark"
                    class="cursor-pointer tooltip tooltip-right hover:text-yellow-300 transition-colors duration-500 ease-in-out"
                    data-tip="Light Mode" aria-label="Light Mode" @click="toggleTheme">
                    <SunIcon class="size-6 " />
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 flex flex-col gap-16 snap-y snap-mandatory overflow-y-scroll">
        <div class="badge badge-neutral p-3 mt-12 md:mt-32 -mb-14 dark:bg-white dark:text-black fade-in-delayed">
            Available for work
        </div>
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <section class="space-y-2 fade-in-delayed">
                <h1 class="text-3xl md:text-5xl font-bold tracking-tight">Hi, I'm Canis - Full Stack Web Developer.</h1>
                <p>I build fast, accessible, beautifully crafted web apps. Focused on DX, performance, and delightful
                    details.</p>
                <div class="flex gap-2 mt-10 flex-wrap">
                    <button
                        class="btn bg-black text-white border-black dark:bg-gray-200 dark:text-black dark:border-gray-200">
                        See my work
                    </button>
                    <button class="btn bg-[#0e76a8] text-white border-[#0e76a8]">
                        LinkedIn
                    </button>
                </div>
                <div class="flex gap-2 flex-wrap font-bold my-8">
                    <span v-for="skill in skills" :key="skill" class="badge bg-gray-200 dark:bg-zinc-600 p-4 text-black dark:text-white">{{ skill }}</span>
                </div>
            </section>
            <aside
                class="items-center justify-center animate-[slideIn_0.8s_ease-out_backwards_1s] [animation-delay:2s]">
                <div class="card bg-base-100 dark:bg-zinc-900 card-lg shadow-md inset-shadow-xs dark:text-black rounded-xl">
                    <div class="card-body flex items-center gap-3 bg-gray-200 dark:bg-zinc-900 dark:text-zinc-300">
                        <div class="avatar avatar-placeholder">
                            <div class="bg-gray-100 text-dark dark:text-zinc-800 w-24 rounded-full">
                                <span class="text-3xl">CC</span>
                            </div>
                        </div>
                        <h2 class="card-title">Web Developer</h2>
                        <p>Open to full-time roles and freelance.</p>
                        <button class="btn btn-neutral">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                            Contact
                        </button>
                    </div>
                </div>
            </aside>
        </div>

        <div class="opacity-0 fadeIn animate-[fadeBlurIn_0.6s_ease-out_forwards] [animation-delay:1.5s] snap-start">
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Featured Projects</h1>
            <p class="text-sm md:text-base mt-2">
                A few of my recent projects.
            </p>
        </div>

        <div class="flex flex-wrap md:flex-nowrap justify-center gap-8 mb-8 px-4 opacity-0 bg-white dark:bg-zinc-900 dark:text-zinc-300
            fadeIn animate-[fadeBlurIn_0.6s_ease-out_forwards] [animation-delay:1.5s] snap-start" id="projects">
            <ProjectCard title="Helpdesk" description="This is a brief description of the project."
                :link="'helpdesk.demo.index'"
                preview="https://www.shutterstock.com/image-vector/image-coming-soon-no-picture-600nw-2450891047.jpg"
                :tags="['Vue', 'Laravel', 'Tailwind']">
            </ProjectCard>
<!-- 
            <ProjectCard title="API Development" description="This is a brief description of the project."
                preview="https://www.shutterstock.com/image-vector/image-coming-soon-no-picture-600nw-2450891047.jpg"
                :tags="['Vue', 'Laravel', 'Tailwind']" link="#">
            </ProjectCard>
-->
            <ProjectCard title="Mining Game" description="This is a brief description of the project."
                :link="'games.mining'"
                preview="https://www.shutterstock.com/image-vector/image-coming-soon-no-picture-600nw-2450891047.jpg"
                :tags="['Vue', 'Laravel', 'Tailwind']">
            </ProjectCard> 
        </div>

        <div id="experience" class="snap-start">
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Experience</h1>
            <p class="text-sm md:text-base mt-2">
                My career journey and experiences.
            </p>

            <div class="mt-12 px-4">
                <ul class="timeline timeline-snap-icon max-md:timeline-compact timeline-vertical">
                    <li>
                        <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="h-5 w-5">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="timeline-start mb-10 md:text-end">
                            <time class="font-mono italic">Current - Nov 2022</time>
                            <div class="text-lg font-black">Web Development Manager</div>
                            Led a team of front-end and back-end developers to deliver scalable web applications.
                            Oversaw project planning, code reviews, and performance optimization, ensuring best
                            practices in
                            accessibility, security, and responsive design. Collaborated cross-functionally with product
                            and design
                            teams to align technical solutions with business goals, while mentoring junior developers to
                            grow
                            technical expertise and leadership skills.
                        </div>
                        <hr />
                    </li>
                    <li>
                        <hr />
                        <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="h-5 w-5">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="timeline-end md:mb-10">
                            <time class="font-mono italic">May 2022 - Nov 2022</time>
                            <div class="text-lg font-black">Web Development Manager (Secondment)</div>
                            iMac is a family of all-in-one Mac desktop computers designed and built by Apple Inc. It has
                            been the primary part of Apple's consumer desktop offerings since its debut in August 1998,
                            and has evolved through seven distinct forms
                        </div>
                        <hr />
                    </li>
                    <li>
                        <hr />
                        <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="h-5 w-5">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="timeline-start mb-10 md:text-end">
                            <time class="font-mono italic">Feb 2022 - May 2022</time>
                            <div class="text-lg font-black">Web Developer</div>
                        </div>
                        <hr />
                    </li>
                    <li>
                        <hr />
                        <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="h-5 w-5">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="timeline-end md:mb-10">
                            <time class="font-mono italic">Feb 2021 - May 2022</time>
                            <div class="text-lg font-black">Junior Web Developer</div>
                        </div>
                        <hr />
                    </li>
                    <li>
                        <hr />
                        <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="h-5 w-5">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="timeline-start mb-10 md:text-end">
                            <time class="font-mono italic">Nov 2018 - Feb 2021</time>
                            <div class="text-lg font-black">Software Engineering Apprentice</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div id="contact" class="lg:w-3/4 mt-2 mb-12 snap-start">
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight my-4">Get In Touch</h1>
            <div class="card w-full bg-base-100 card-lg shadow-sm  bg-gray-50 dark:text-black">
                <div class="card-body flex gap-4">
                    <h2 class="card-title">Send a message</h2>
                    <p class="text-sm md:text-base text-gray-600 mt-2 mb-4">
                        I'll get back to you within 1-2 business days.</p>
                    <div class="flex flex-col md:flex-row gap-4">
                        <label class="input md:flex-1 w-full bg-zinc-200">
                            <span class="label">Name</span>
                            <input type="text" placeholder="John Smith" />
                        </label>
                        <label class="input md:flex-1 w-full bg-zinc-200">
                            <span class="label">Email</span>
                            <input type="text" placeholder="you@example.com" />
                        </label>
                    </div>
                    <label class="input border-1 w-full bg-zinc-200">
                        <span class="label">Subject</span>
                        <input type="text" placeholder="Let's work together" />
                    </label>
                    <textarea class="textarea w-full min-h-[200px] bg-zinc-200" placeholder="Tell me about ..."></textarea>
                    <div class="justify-end card-actions mt-4">
                        <button class="btn btn-neutral">Send Message</button>
                    </div>
                </div>
            </div>
        </div>

    </main>
</template>

<style>
.theme-transition * {
    transition: background-color 1s ease-in-out,
        color 1s ease-in-out,
        border-color 1s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0
    }

    to {
        opacity: 1
    }
}

.fade-in-nav {
    opacity: 0;
    animation: fadeIn 0.8s ease forwards;
    animation-delay: 0.5s;
}

.fade-in-delayed {
    opacity: 0;
    animation: fadeIn 0.8s ease forwards;
    animation-delay: 1s;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(50px);
        /* start off to the right */
    }

    to {
        opacity: 1;
        transform: translateX(0);
        /* end in place */
    }
}

@keyframes fadeBlurIn {
    from {
        opacity: 0;
        filter: blur(6px);
        transform: translateY(8px);
    }

    to {
        opacity: 1;
        filter: blur(0);
        transform: translateY(0);
    }
}
</style>