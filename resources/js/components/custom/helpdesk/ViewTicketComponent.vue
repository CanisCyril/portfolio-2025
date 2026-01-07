<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import { toast } from 'vue3-toastify';

dayjs.extend(relativeTime)

import { Field, Form, ErrorMessage, configure } from 'vee-validate';
import * as yup from 'yup';

import {
    ArrowLeftIcon, ShieldCheckIcon, WifiIcon, TagIcon,
    CalendarIcon, ClockIcon, PaperClipIcon, ArrowDownTrayIcon
} from '@heroicons/vue/24/outline'

import { statusColor } from '@/utils/helpdesk/statusColor'
import { priorityColor } from '@/utils/helpdesk/priorityColor'
// import { ArrowBigDownDashIcon } from 'lucide-vue-next';


const swalTheme = document.documentElement.classList.contains('dark')
    ? 'dark'   // requires the dark theme css to be imported
    : 'auto';

// Components

configure({
    validateOnChange: true, // controls if `change` events should trigger validation with `handleChange` handler
});


const schema = yup.object({
    body: yup.string().required().label("Comment"),
});

const priorities = ref([
    { id: 1, name: 'Low' },
    { id: 2, name: 'Medium' },
    { id: 3, name: 'High' },
    { id: 4, name: 'Business Critical' },
]);

const categories = ref([
    { id: 1, name: 'Internet' },
    { id: 2, name: 'Email' },
    { id: 3, name: 'Software' },
    { id: 4, name: 'Hardware' },
    { id: 4, name: 'Other' },
]);

const statuses = ref(['Open', 'Assigned', 'Closed']);

const assignees = ref([]);
const selectedAssignee = ref(null);
const selectedStatus = ref(null);
// const body = ref(null);
// const attachments = ref([]);

// ADD PROPER TYPES

const ticket = defineModel<any>('ticket')

const props = defineProps<{
    auth?: { user?: any }
    permissions?: any
}>()

const emit = defineEmits(['close'])

const goBack: () => void = () => emit('close')

const getAssignees = () => axios.get(route('helpdesk.assignees'))
    .then(({ data }) => {

        assignees.value = data.data
    })
    .catch(err => {
        console.error(err)
    })

onMounted(() => {
    getAssignees();

    formatAssignee();

    selectedStatus.value = ticket.value.status[0].toUpperCase() + ticket.value.status.slice(1);
    console.log('SELECTED STATUS', selectedStatus.value)
    

})

const formatAssignee = () => {
    let formatSelectedAssignee = { id: props.ticket.assignee?.id, name: props.ticket.assignee?.name };
    selectedAssignee.value = props.ticket.assignee ? formatSelectedAssignee : null
}

const formatSizeKb = (bytes?: number | null) => {
    if (!bytes) return '0 KB';
    return `${Math.round(bytes / 1024)} KB`;
};

const onSelectChange = () => {

    Swal.fire({
        title: `Are you sure`,
        text: `Assign ticket to ${selectedAssignee.value?.name}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#14A44D",
        cancelButtonColor: "#999999",
        confirmButtonText: "Update",
        theme: swalTheme
    }).then((result) => {
        if (result.isConfirmed) {
            axios.patch(
                route('helpdesk.ticket.update.assignee', props.ticket.id),
                { assigned_to_id: selectedAssignee.value?.id }
            )
                .then(({ data }) => {
                    console.log('TICKET', data)
                    ticket.value.assignee = data.assignee
                    ticket.value.comments = data.comments

                    toast.success(`Assigned to ${selectedAssignee.value?.name}`)
                })
                .catch(err => {
                    console.error(err)
                })

        } else {
            formatAssignee();
            // selectedAssignee.value = props.ticket.assignee ? props.ticket.assignee : null
        }
    });
}

const onSelectStatusChange = () => {

    Swal.fire({
        title: `Are you sure`,
        text: `Update status to ${selectedStatus.value}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#14A44D",
        cancelButtonColor: "#999999",
        confirmButtonText: "Update",
        theme: swalTheme
    }).then((result) => {
        if (result.isConfirmed) {
            axios.patch(
                route('helpdesk.ticket.update.status', props.ticket.id),
                { status: selectedStatus.value.toLowerCase() }
            )
                .then(({ data }) => {
                    console.log('SUCCESS STATUS UPDATE', data)
                    ticket.value.status = data.status
                    ticket.value.comments = data.comments

                    toast.success(`Status updated to ${selectedStatus.value}`)
                })
                .catch(err => {
                    console.error(err)
                })

        } else {
            // selectedAssignee.value = props.ticket.assignee ? props.ticket.assignee : null
        }
    });
}

function addComment(values, { resetForm }) {
    console.log('ADD COMMENT VALUES', values.body);
    Swal.fire({
        title: `Are you sure`,
        text: `Add comment to this ticket?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#14A44D",
        cancelButtonColor: "#999999",
        confirmButtonText: "Update",
        theme: swalTheme
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(
                route('helpdesk.ticket.store.comment'),
                {
                    ticket_id: props.ticket.id,
                    body: values.body,
                })
                .then(({ data }) => {

                    ticket.value.comments.push(data)
                    toast.success('Comment Added');

                            resetForm()
                })
                .catch(err => {
                    console.error(err)
                })

        }
    });
}

function uploadAttachment(ticketId: number, event: Event) {
    Swal.fire({
        title: `Are you sure`,
        text: `Add attachment(s) to this ticket?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#14A44D",
        cancelButtonColor: "#999999",
        confirmButtonText: "Update",
        theme: swalTheme
    }).then((result) => {
        if (result.isConfirmed) {

            const formData = new FormData();

            Array.from(event?.target?.files).forEach(file => {
                formData.append('attachments[]', file);
            });

            axios
                .post(route('helpdesk.ticket.store.attachment', ticketId), formData)
                .then(({ data }) => {
                    toast.success('Attachment(s) uploaded successfully');
                    ticket.value = data;
                    // event.target?.value = '';
                })
                .catch((err) => {
                    console.error(err);
                });
        }
    });
}

// setFieldValue('body', null)

</script>

<template>

    <!-- <Head>
        <title>Helpdesk - View Ticket</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </Head> -->
    <div class="min-h-screen bg-neutral-200 dark:bg-zinc-900">
        <nav
            class="flex flex-row justify-between items-center mb-4 py-2 px-4 bg-neutral-50 dark:bg-zinc-950 rounded-sm min-h-[50px]">
            <div class="flex flex-row items-center">
                <span @click="goBack" class="hover:bg-zinc-200 dark:hover:bg-zinc-700 p-1 rounded-md">
                    <ArrowLeftIcon class="size-6 cursor-pointer" />
                </span>
                <div class="divider divider-horizontal"></div>
                <span class="text-zinc-500">Ticket / #{{ ticket.id }} </span>
            </div>
        </nav>
        <main class="container mx-auto max-w-6xl text-zinc-900 dark:text-zinc-100">
            <div
                class="md:min-h-[700px] flex flex-col-reverse md:grid md:[grid-template-columns:70%_30%] gap-4 p-4 md:mt-12">
                <div class="flex flex-col gap-8 p-4">
                    <div class="card w-96 bg-gray-50 dark:bg-zinc-950 shadow-sm w-full">
                        <div class="card-body">
                            <h1 class="text-2xl font-bold leading-tight break-words whitespace-pre-line">{{
                                ticket.title }}
                            </h1>
                            <p class="text-sm text-zinc-500 px-1">{{ dayjs(ticket.created_at).fromNow() }}</p>
                            <p class="text-md pt-4 text-wrap break-words whitespace-pre-line">{{ ticket.description }}
                            </p>
                        </div>
                    </div>
                    <div class="card w-96 bg-gray-50 shadow-sm w-full dark:bg-zinc-950 mb-8">
                        <div class="card-body flex gap-4">
                            <h1 class="text-xl font-bold leading-tight">Activity</h1>
                            <p class="text-sm text-zinc-500 px-1">Conversations and updates</p>
                            <div class="flex flex-col gap-4 mt-2">
                                <div v-for="comment in ticket.comments"
                                    class="p-4 border rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-medium">{{ comment.author.name }}</span>
                                        <span class="text-xs text-gray-500">{{ dayjs(comment.created_at).fromNow()
                                        }}</span>
                                    </div>
                                    <p class="text-sm dark:text-zinc-50 whitespace-pre-wrap mt-4">
                                        {{ comment.body }}
                                    </p>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <Form :validation-schema="schema" @submit="addComment"
                                v-slot="{ errors, values, setFieldValue }">

                                <p class="text-sm text-zinc-500 px-1 mb-2">Add a comment</p>
                                <Field name="body" v-slot="{ field, meta }">
                                    <textarea v-bind="field"
                                        class="textarea focus:outline-none focus:border-2 focus:border-sky-900 bg-gray-200 dark:bg-zinc-800 border-1 h-42 w-full"
                                        :class="{
                                            'input-error': errors.body,
                                            'input-success': !errors.body && meta.dirty
                                        }" placeholder="Type here..." :aria-invalid="!errors.body"
                                        :aria-describedby="errors.body ? 'body-error' : undefined">
                                    </textarea>
                                </Field>
                                <ErrorMessage name="body" class="first-letter:uppercase text-error p-2 text-sm" />
                                <div class="w-full mt-3">
                                    <button class="btn btn-neutral dark:btn-success float-right">Add
                                        Comment</button>
                                </div>
                            </Form>
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Pick a file</legend>
                                <input type="file" multiple class="file-input bg-gray-200 dark:bg-zinc-800 p-1"
                                    @change="uploadAttachment(ticket.id, $event)" />
                                <label class="label">Max size 2MB</label>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-8 p-4">
                    <div class="card w-96 bg-gray-50 shadow-sm w-full dark:bg-zinc-950">
                        <div class="card-body">
                            <h1 class="text-xl font-bold leading-tight">Ticket Details</h1>
                            <div class="flex flex-row justify-between gap-4 mt-4 text-zinc-500">
                                <div class="flex flex-row gap-1 items-center">
                                    <ShieldCheckIcon class="size-5" />
                                    <span>Status</span>
                                </div>
                                <span class="badge p-1 w-full max-w-[80px] capitalize"
                                    :class="statusColor(ticket.status)">{{ ticket.status }}</span>
                            </div>
                            <div class="flex flex-row justify-between gap-4 mt-4 text-zinc-500">
                                <div class="flex flex-row gap-1 items-center">
                                    <WifiIcon class="size-5" />
                                    <span>Priority</span>
                                </div>
                                <span class="badge p-1 w-full max-w-[80px]" :class="priorityColor('medium')">Med</span>
                            </div>
                            <div class="flex flex-row justify-between gap-4 mt-4 text-zinc-500">
                                <div class="flex flex-row gap-1 items-center">
                                    <TagIcon class="size-5" />
                                    <span>Category</span>
                                </div>
                                <span class="badge badge-ghost p-1 w-full max-w-[80px]">Internet</span>
                            </div>
                            <div class="flex flex-row justify-between gap-4 mt-4 text-zinc-500">
                                <div class="flex flex-row gap-1 items-center">
                                    <CalendarIcon class="size-5" />
                                    <span>Created At</span>
                                </div>
                                <span class="p-1 w-full max-w-[80px]">12/12/2025 13:30</span>
                            </div>
                            <div class="flex flex-row justify-between gap-4 mt-4 text-zinc-500">
                                <div class="flex flex-row gap-1 items-center">
                                    <ClockIcon class="size-5" />
                                    <span>Updated At</span>
                                </div>
                                <span class="p-1 w-full max-w-[80px]">12/12/2025 13:30</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="ticket.assigned_to_id" class="card w-96 bg-gray-50 shadow-sm w-full dark:bg-zinc-950">
                        <div class="card-body">
                            <h1 class="text-xl font-bold leading-tight">Assigned to</h1>
                            <div class="flex flex-row items-center gap-4 mt-4">
                                <div class="avatar">
                                    <div class="w-12 rounded-full">
                                        <img src="https://img.daisyui.com/images/profile/demo/yellingcat@192.webp" />
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <h1 class="text-md font-bold leading-tight">{{ ticket.assignee.name }}</h1>
                                    <p class="text-sm text-zinc-500">{{ ticket.assignee.role.display_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="props.permissions.adminAccess"
                        class="card w-96 bg-gray-50 shadow-sm w-full dark:bg-zinc-950">
                        <div class="card-body">
                            <h1 class="text-xl font-bold leading-tight">Admin Options</h1>
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Select assignee</legend>
                                <select v-model="selectedAssignee" @change="onSelectChange"
                                    class="select bg-gray-200 dark:bg-zinc-800 w-full">
                                    <option disabled selected :value="null">Select User</option>
                                    {{ assignees }}
                                    <option v-for="assignee in assignees" :key="assignee.id" :value="assignee">
                                        {{ assignee.name }}
                                    </option>
                                </select>
                            </fieldset>
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Update Status</legend>
                                <select v-model="selectedStatus" @change="onSelectStatusChange"
                                    class="select bg-gray-200 dark:bg-zinc-800 capitalize w-full">
                                    <option v-for="status in statuses" :key="status" :value="status">
                                        {{ status }}
                                    </option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div v-if="ticket.attachments?.length"
                        class="card w-96 bg-gray-50 shadow-sm w-full dark:bg-zinc-950">
                        <div class="card-body">
                            <h1 class="text-xl font-bold leading-tight">Attachments</h1>
                            <div v-for="attachment in ticket.attachments"
                                class="flex flex-row justify-between items-center border gap-4 mt-4 p-4 rounded-lg bg-gray-200 dark:bg-zinc-800">
                                <div class="flex flex-row items-center gap-2">
                                    <PaperClipIcon class="size-5" />
                                    <div class="flex flex-col">
                                        <span class="break-all">{{ attachment.original_name }}</span>
                                        <span class="text-xs text-zinc-500">{{ formatSizeKb(attachment.size) }}</span>
                                    </div>
                                </div>
                                <span class="hover:bg-zinc-300 hover:dark:bg-zinc-800 p-2 rounded-md cursor-pointer">
                                    <a :href="route('helpdesk.ticket.download.attachment', attachment.id)">
                                        <ArrowDownTrayIcon class="size-5" />
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
