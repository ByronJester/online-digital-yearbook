<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, useForm } from '@inertiajs/vue3';

const formLogo = useForm({
    file: '',
});

const formGreeting = useForm({
    content: '',
    file: ''
});

const formStory = useForm({
    student_name: '',
    content: '',
    file: ''
});

const formHistory = useForm({
    content: '',
    file: ''
});

const formHymn = useForm({
    content: '',
    file: ''
});

const formMission = useForm({
    content: '',
    file: ''
});

const formVision = useForm({
    content: '',
    file: ''
});

const formProgram = useForm({
    program_name: '',
    content: '',
    file: ''
});

const formFaq = useForm({
    content: '',
    file: ''
});

const handleFileChange = (e, type) => {
    console.log(e)
    console.log(type)

    var file = event.target.files[0]

    switch(type) {
        case 'logo':
            formLogo.file = file
            break;
        case 'greeting':
            formGreeting.file = file
            break;
        case 'story':
            formStory.file = file
            break;
        case 'history':
            formHistory.file = file
            break;
        case 'hymn':
            formHymn.file = file
            break;
        case 'mission':
            formMission.file = file
            break;
        case 'vision':
            formVision.file = file
            break;
        case 'program':
            formProgram.file = file
            break;
    }
}

const saveLogo = () => {
    formLogo.post(route('home-page-management-logo'), {
        onSuccess: (page) => {
            location.reload()
        },
        onError: (errors) => {

        }
    });
}

const saveGreeting = () => {
    formGreeting.post(route('home-page-management-greeting'), {
        onSuccess: (page) => {
            location.reload()
        },
        onError: (errors) => {

        }
    });
}

const saveStory = () => {
    formStory.post(route('home-page-management-story'), {
        onSuccess: (page) => {
            location.reload()
        },
        onError: (errors) => {

        }
    });
}

const saveHistory = () => {
    formHistory.post(route('home-page-management-history'), {
        onSuccess: (page) => {
            location.reload()
        },
        onError: (errors) => {

        }
    });
}

const saveHymn = () => {
    formHymn.post(route('home-page-management-hymn'), {
        onSuccess: (page) => {
            location.reload()
        },
        onError: (errors) => {

        }
    });
}

const saveMission = () => {
    formMission.post(route('home-page-management-mission'), {
        onSuccess: (page) => {
            location.reload()
        },
        onError: (errors) => {

        }
    });
}

const saveVision = () => {
    formVision.post(route('home-page-management-vision'), {
        onSuccess: (page) => {
            location.reload()
        },
        onError: (errors) => {

        }
    });
}

const saveProgram = () => {
    formProgram.post(route('home-page-management-program'), {
        onSuccess: (page) => {
            location.reload()
        },
        onError: (errors) => {

        }
    });
}

const saveFaq = () => {
    formFaq.post(route('home-page-management-faq'), {
        onSuccess: (page) => {
            location.reload()
        },
        onError: (errors) => {

        }
    });
}





</script>

<template>
    <Head title="Homepage Management" />

    <AuthenticatedLayout>
        <div class="w-full p-3 ">
            <div class="flex flex-row">
                <div class="w-full mx-1 border-b-2 border-black">
                    <p class="mb-5 text-2xl">
                        Logo
                    </p>

                    <input type="file" @change="handleFileChange($event, 'logo')"/>

                    <br>
                    <button class="bg-blue-500 px-5 py-2 mt-3 rounded-md text-white text-xl"
                        @click="saveLogo()"
                    >
                        Save
                    </button>
                </div>

                <div class="w-full mx-1 border-b-2 border-black">
                    <p class="mb-5 text-2xl">
                        Greeting Message
                    </p>

                    <textarea class="w-full rounded-md" rows="4" cols="40" placeholder="Greeting"
                        v-model="formGreeting.content"
                    />

                    <input type="file" @change="handleFileChange($event, 'greeting')"/>

                    <br>
                    <button class="bg-blue-500 px-5 py-2 mt-3 rounded-md text-white text-xl"
                        @click="saveGreeting()"
                    >
                        Save
                    </button>
                </div>

                <div class="w-full mx-1 border-b-2 border-black">
                    <p class="mb-5 text-2xl">
                        Alumni Success Stories
                    </p>

                    <input type="text" class="w-full mb-1 rounded-md" placeholder="Student Name"
                        v-model="formStory.student_name"
                    />

                    <textarea class="w-full rounded-md" rows="4" cols="40" placeholder="Story"
                        v-model="formStory.content"
                    />

                    <input type="file" class="mb-5" @change="handleFileChange($event, 'story')"/>

                    <br>
                    <button class="bg-blue-500 px-5 py-2 my-3 rounded-md text-white text-xl"
                        @click="saveStory()"
                    >
                        Save
                    </button>
                </div>

            </div>

            <div class="flex flex-row">
                <div class="w-full mx-1 border-b-2 border-black">
                    <p class="mb-5 text-2xl">
                        Brief History
                    </p>

                    <textarea class="w-full rounded-md" rows="4" cols="40" placeholder="Brief History"
                        v-model="formHistory.content"
                    />

                    <input type="file" class="mb-5" @change="handleFileChange($event, 'history')"/>


                    <br>
                    <button class="bg-blue-500 px-5 py-2 my-3 rounded-md text-white text-xl"
                        @click="saveHistory()"
                    >
                        Save
                    </button>
                </div>

                <div class="w-full mx-1 border-b-2 border-black">
                    <p class="mb-5 text-2xl">
                        School Hymn
                    </p>

                    <textarea class="w-full rounded-md" rows="4" cols="40" placeholder="Hymn"
                    v-model="formHymn.content"
                    />

                    <input type="file" class="mb-5" @change="handleFileChange($event, 'hymn')"/>

                    <br>
                    <button class="bg-blue-500 px-5 py-2 my-3 rounded-md text-white text-xl"
                        @click="saveHymn()"
                    >
                        Save
                    </button>
                </div>

                <div class="w-full mx-1 border-b-2 border-black">
                    <p class="mb-5 text-2xl">
                        Mission Statement
                    </p>

                    <textarea class="w-full rounded-md" rows="4" cols="40" placeholder="Mission"
                        v-model="formMission.content"
                    />

                    <input type="file" class="mb-5" @change="handleFileChange($event, 'mission')"/>

                    <br>
                    <button class="bg-blue-500 px-5 py-2 my-3 rounded-md text-white text-xl"
                        @click="saveMission()"
                    >
                        Save
                    </button>
                </div>
            </div>

            <div class="flex flex-row">
                <div class="w-full mx-1 border-b-2 border-black">
                    <p class="mb-5 text-2xl">
                        Vision Statement
                    </p>

                    <textarea class="w-full rounded-md" rows="4" cols="40" placeholder="Vision"
                        v-model="formVision.content"
                    />

                    <input type="file" class="mb-5" @change="handleFileChange($event, 'vision')"/>

                    <br>
                    <button class="bg-blue-500 px-5 py-2 my-3 rounded-md text-white text-xl"
                        @click="saveVision()"
                    >
                        Save
                    </button>
                </div>

                <div class="w-full mx-1 border-b-2 border-black">
                    <p class="mb-5 text-2xl">
                        Programs
                    </p>

                    <input type="text" class="w-full mb-1 rounded-md" placeholder="Program Name"
                        v-model="formProgram.program_name"
                    />

                    <textarea class="w-full rounded-md" rows="4" cols="40" placeholder="Program Description"
                        v-model="formProgram.content"
                    />

                    <input type="file" class="mb-5" @change="handleFileChange($event, 'program')"/>

                    <br>
                    <button class="bg-blue-500 px-5 py-2 my-3 rounded-md text-white text-xl"
                        @click="saveProgram()"
                    >
                        Save
                    </button>
                </div>

                <div class="w-full mx-1 border-b-2 border-black">
                    <p class="mb-5 text-2xl">
                        FAQ
                    </p>

                    <textarea class="w-full rounded-md" rows="4" cols="40" placeholder="FAQ"
                        v-model="formFaq.content"
                    />

                    <br>
                    <button class="bg-blue-500 px-5 py-2 my-3 rounded-md text-white text-xl"
                        @click="saveFaq()"
                    >
                        Save
                    </button>
                </div>


            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style>


</style>
