<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { VueSpinner } from 'vue3-spinners';

defineProps({
    logos: {
        type: Array,
    },
    greetings: {
        type: Array,
    },
    stories: {
        type: Array,
    },
    histories: {
        type: Array,
    },
    hymns: {
        type: Array,
    },
    missions: {
        type: Array,
    },
    visions: {
        type: Array,
    },
    programs: {
        type: Array,
    },
    faqs: {
        type: Array,
    },
});

const formLogo = useForm({
    file: '',
});

const formGreeting = useForm({
    id: null,
    content: '',
    file: ''
});

const formStory = useForm({
    id: null,
    student_name: '',
    content: '',
    file: ''
});

const formHistory = useForm({
    id: null,
    content: '',
    file: ''
});

const formHymn = useForm({
    id: null,
    content: '',
    file: ''
});

const formMission = useForm({
    id: null,
    content: '',
    file: ''
});

const formVision = useForm({
    id: null,
    content: '',
    file: ''
});

const formProgram = useForm({
    id: null,
    program_name: '',
    content: '',
    file: ''
});

const formFaq = useForm({
    id: null,
    content: '',
    file: ''
});

const handleFileChange = (e, type) => {
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

const loading = ref(false)

const saveLogo = () => {
    swal({
        title: "Are you sure to save this logo?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            setTimeout(() => {
                formLogo.post(route('home-page-management-logo'), {
                    onSuccess: (page) => {
                        swal("Successfully save.", {
                            icon: "success",
                        });
                        location.reload()
                        loading.value = false
                    },
                    onError: (errors) => {
                        swal("Error saving.");
                        loading.value = false
                    }
                });

            }, 2000);
        }
    });


}

const saveGreeting = () => {
    swal({
        title: "Are you sure to save this greeting?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            setTimeout(() => {
                formGreeting.post(route('home-page-management-greeting'), {
                    onSuccess: (page) => {
                        location.reload()
                        loading.value = false
                    },
                    onError: (errors) => {
                        loading.value = false
                    }
                });

            }, 2000);


        }
    });


}

const saveStory = () => {
    swal({
        title: "Are you sure to save this story?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            setTimeout(() => {
                formStory.post(route('home-page-management-story'), {
                    onSuccess: (page) => {
                        location.reload()
                        loading.value = false
                    },
                    onError: (errors) => {
                        loading.value = false
                    }
                });

            }, 2000);


        }
    });
}

const saveHistory = () => {
    swal({
        title: "Are you sure to save this history?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            setTimeout(() => {
                formHistory.post(route('home-page-management-history'), {
                    onSuccess: (page) => {
                        location.reload()
                        loading.value = false
                    },
                    onError: (errors) => {
                        loading.value = false
                    }
                });

            }, 2000);


        }
    });
}

const saveHymn = () => {
    swal({
        title: "Are you sure to save this hymn?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            setTimeout(() => {
                formHymn.post(route('home-page-management-hymn'), {
                    onSuccess: (page) => {
                        location.reload()
                        loading.value = false
                    },
                    onError: (errors) => {
                        loading.value = false
                    }
                });

            }, 2000);


        }
    });
}

const saveMission = () => {
    swal({
        title: "Are you sure to save this mission?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            setTimeout(() => {
                formMission.post(route('home-page-management-mission'), {
                    onSuccess: (page) => {
                        location.reload()
                        loading.value = false
                    },
                    onError: (errors) => {
                        loading.value = false
                    }
                });

            }, 2000);


        }
    });
}

const saveVision = () => {
    swal({
        title: "Are you sure to save this vision?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            setTimeout(() => {
                formVision.post(route('home-page-management-vision'), {
                    onSuccess: (page) => {
                        location.reload()
                        loading.value = false
                    },
                    onError: (errors) => {
                        loading.value = false
                    }
                });

            }, 2000);
        }
    });


}

const saveProgram = () => {
    swal({
        title: "Are you sure to save this program?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            setTimeout(() => {
                formProgram.post(route('home-page-management-program'), {
                    onSuccess: (page) => {
                        location.reload()
                        loading.value = false
                    },
                    onError: (errors) => {
                        loading.value = false
                    }
                });

            }, 2000);
        }
    });

}

const saveFaq = () => {
    swal({
        title: "Are you sure to save this FAQ?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            setTimeout(() => {
                formFaq.post(route('home-page-management-faq'), {
                    onSuccess: (page) => {
                        location.reload()
                        loading.value = false
                    },
                    onError: (errors) => {
                        loading.value = false
                    }
                });

            }, 2000);
        }
    });


}

const logoAction = ({ action, row }) => {
    if (action == 'delete') {
        swal({
            title: "Are you sure to delete this logo?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-delete-data'), { id: row.id, table: 'logos'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    } else if (action == 'use') {
        swal({
            title: "Are you sure to use this logo?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-use-data'), { id: row.id, table: 'logos'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    } else if (action == 'edit') {

    }
}

const greetingsAction = ({ action, row }) => {
    if (action == 'delete') {
        swal({
            title: "Are you sure to delete this greeting?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-delete-data'), { id: row.id, table: 'greetings'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    }  else if (action == 'edit') {
        formGreeting.id = row.id
        formGreeting.content = row.content
        formGreeting.file = row.file
    }
}

const storiesAction = ({ action, row }) => {
    if (action == 'delete') {
        swal({
            title: "Are you sure to delete this story?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-delete-data'), { id: row.id, table: 'success_stories'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    }  else if (action == 'edit') {
        formStory.id = row.id
        formStory.student_name = row.student_name
        formStory.content = row.content
        formStory.file = row.file
    }
}

const historiesAction = ({ action, row }) => {
    if (action == 'delete') {
        swal({
            title: "Are you sure to delete this story?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-delete-data'), { id: row.id, table: 'histories'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    }  else if (action == 'edit') {
        formHistory.id = row.id
        formHistory.content = row.content
        formHistory.file = row.file
    }
}

const hymnAction = ({ action, row }) => {

    if (action == 'delete') {
        swal({
            title: "Are you sure to delete this hymn?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-delete-data'), { id: row.id, table: 'hymn'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    } else if (action == 'use') {
        swal({
            title: "Are you sure to use this hymn?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-use-data'), { id: row.id, table: 'hymn'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    } else if (action == 'edit') {
        formHymn.id = row.id
        formHymn.content = row.content
        formHymn.file = row.file
    }
}


const missionsAction = ({ action, row }) => {

    if (action == 'delete') {
        swal({
            title: "Are you sure to delete this mission?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-delete-data'), { id: row.id, table: 'mission_statements'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    } else if (action == 'use') {
        swal({
            title: "Are you sure to use this mission?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-use-data'), { id: row.id, table: 'mission_statements'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    } else if (action == 'edit') {
        formMission.id = row.id
        formMission.content = row.content
        formMission.file = row.file
    }
}


const visionsAction = ({ action, row }) => {

    if (action == 'delete') {
        swal({
            title: "Are you sure to delete this vision?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-delete-data'), { id: row.id, table: 'vision_statements'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    } else if (action == 'use') {
        swal({
            title: "Are you sure to use this vision?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-use-data'), { id: row.id, table: 'vision_statements'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    } else if (action == 'edit') {
        formVision.id = row.id
        formVision.content = row.content
        formVision.file = row.file
    }
}


const programsAction = ({ action, row }) => {
    if (action == 'delete') {
        swal({
            title: "Are you sure to delete this program?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-delete-data'), { id: row.id, table: 'programs'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    } else if (action == 'use') {
        swal({
            title: "Are you sure to use this program?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-use-data'), { id: row.id, table: 'programs'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    } else if (action == 'edit') {
        formProgram.id = row.id
        formProgram.program_name = row.program_name
        formProgram.content = row.content
        formProgram.file = row.file
    }
}

const faqsAction = ({ action, row }) => {
    if (action == 'delete') {
        swal({
            title: "Are you sure to delete this faq?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-delete-data'), { id: row.id, table: 'faqs'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    } else if (action == 'use') {
        swal({
            title: "Are you sure to use this faq?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                Inertia.post(route('hpm-use-data'), { id: row.id, table: 'faqs'}, {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {

                    },
                });

            }
        });
    } else if (action == 'edit') {
        formFaq.id = row.id
        formFaq.content = row.content
    }
}



const activeTab = ref('logo');


</script>

<template>
    <Head title="Homepage Management" />

    <AuthenticatedLayout>
        <div class="w-full p-3 h-full max-h-screen">
            <div class="tabs bg-white">
                <button @click="activeTab = 'logo'" :class="{ 'active': activeTab === 'logo' }">Logo</button>
                <button @click="activeTab = 'greeting'" :class="{ 'active': activeTab === 'greeting' }">Greeting</button>
                <button @click="activeTab = 'story'" :class="{ 'active': activeTab === 'story' }">Alumni Success Stories</button>
                <button @click="activeTab = 'history'" :class="{ 'active': activeTab === 'history' }">History</button>
                <button @click="activeTab = 'hymn'" :class="{ 'active': activeTab === 'hymn' }">Hymn</button>
                <button @click="activeTab = 'mission'" :class="{ 'active': activeTab === 'mission' }">Mission</button>
                <button @click="activeTab = 'vision'" :class="{ 'active': activeTab === 'vision' }">Vision</button>
                <button @click="activeTab = 'program'" :class="{ 'active': activeTab === 'program' }">Programs</button>
                <button @click="activeTab = 'faq'" :class="{ 'active': activeTab === 'faq' }">FAQ</button>
            </div>


            <div v-if="activeTab === 'logo'" class="tab-content">
                <div class="w-full flex justify-center items-center" v-if="loading">
                    <VueSpinner size="50" color="red" />
                 </div>

                <div class="w-full border border-black" v-else>
                    <div class="w-full p-3">
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

                </div>

                <div class="w-full mt-10" v-if="logos.length > 0">
                    <Table
                        :headers="['File']"
                        :rows="logos"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="false"
                        :showDelete="true"
                        :showCopy="false"
                        :showSearch="false"
                        :showUse="true"
                        :table="'logo'"
                        @action-event="logoAction"
                    />
                </div>
            </div>

            <div v-if="activeTab === 'greeting'" class="tab-content">
                <div class="w-full flex justify-center items-center" v-if="loading">
                    <VueSpinner size="50" color="red" />
                 </div>

                <div class="w-full border border-black" v-else>
                    <div class="w-full p-3">
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

                </div>

                <div class="w-full mt-10" v-if="greetings.length > 0">
                    <Table
                        :headers="['Content', 'File']"
                        :rows="greetings"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="true"
                        :showDelete="true"
                        :showCopy="false"
                        :showSearch="false"
                        :showUse="false"
                        :table="'greeting'"
                        @action-event="greetingsAction"
                    />
                </div>
            </div>

            <div v-if="activeTab === 'story'" class="tab-content">
                <div class="w-full flex justify-center items-center" v-if="loading">
                    <VueSpinner size="50" color="red" />
                 </div>

                <div class="w-full border border-black" v-else>
                    <div class="w-full p-3">
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

                <div class="w-full mt-10" v-if="stories.length > 0">
                    <Table
                        :headers="['Student Name','Content', 'File']"
                        :rows="stories"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="true"
                        :showDelete="true"
                        :showCopy="false"
                        :showSearch="false"
                        :showUse="false"
                        :table="'stories'"
                        @action-event="storiesAction"
                    />
                </div>

            </div>

            <div v-if="activeTab === 'history'" class="tab-content">
                <div class="w-full flex justify-center items-center" v-if="loading">
                    <VueSpinner size="50" color="red" />
                 </div>

                <div class="w-full border border-black" v-else>
                    <div class="w-full p-3">
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

                </div>

                <div class="w-full mt-10" v-if="histories.length > 0">
                    <Table
                        :headers="['Content', 'File']"
                        :rows="histories"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="true"
                        :showDelete="true"
                        :showCopy="false"
                        :showSearch="false"
                        :showUse="false"
                        :table="'history'"
                        @action-event="historiesAction"
                    />
                </div>
            </div>

            <div v-if="activeTab === 'hymn'" class="tab-content">
                <div class="w-full flex justify-center items-center" v-if="loading">
                    <VueSpinner size="50" color="red" />
                 </div>

                <div class="w-full border border-black" v-else>
                    <div class="w-full p-3">
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

                </div>

                <div class="w-full mt-10" v-if="hymns.length > 0">
                    <Table
                        :headers="['Content', 'File']"
                        :rows="hymns"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="true"
                        :showDelete="true"
                        :showCopy="false"
                        :showSearch="false"
                        :showUse="true"
                        :table="'hymn'"
                        @action-event="hymnAction"
                    />
                </div>
            </div>

            <div v-if="activeTab === 'mission'" class="tab-content">
                <div class="w-full flex justify-center items-center" v-if="loading">
                    <VueSpinner size="50" color="red" />
                 </div>

                <div class="w-full border border-black" v-else>
                    <div class="w-full p-3">
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

                <div class="w-full mt-10" v-if="missions.length > 0">
                    <Table
                        :headers="['Content','File']"
                        :rows="missions"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="true"
                        :showDelete="true"
                        :showCopy="false"
                        :showSearch="false"
                        :showUse="true"
                        :table="'mission'"
                        @action-event="missionsAction"
                    />
                </div>
            </div>

            <div v-if="activeTab === 'vision'" class="tab-content">
                <div class="w-full flex justify-center items-center" v-if="loading">
                    <VueSpinner size="50" color="red" />
                 </div>

                <div class="w-full border border-black" v-else>
                    <div class="w-full p-3">
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

                </div>

                <div class="w-full mt-10" v-if="visions.length > 0">
                    <Table
                        :headers="['Content','File']"
                        :rows="visions"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="true"
                        :showDelete="true"
                        :showCopy="false"
                        :showSearch="false"
                        :showUse="true"
                        :table="'mission'"
                        @action-event="visionsAction"
                    />
                </div>
            </div>

            <div v-if="activeTab === 'program'" class="tab-content">
                <div class="w-full flex justify-center items-center" v-if="loading">
                    <VueSpinner size="50" color="red" />
                 </div>

                <div class="w-full border border-black" v-else>
                    <div class="w-full p-3">
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

                </div>

                <div class="w-full mt-10" v-if="programs.length > 0">
                    <Table
                        :headers="['Program Name', 'Content','File']"
                        :rows="programs"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="true"
                        :showDelete="true"
                        :showCopy="false"
                        :showSearch="false"
                        :showUse="true"
                        :table="'program'"
                        @action-event="programsAction"
                    />
                </div>
            </div>

            <div v-if="activeTab === 'faq'" class="tab-content">
                <div class="w-full flex justify-center items-center" v-if="loading">
                    <VueSpinner size="50" color="red" />
                 </div>

                <div class="w-full border border-black" v-else>
                    <div class="w-full p-3">
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

                <div class="w-full mt-10" v-if="faqs.length > 0">
                    <Table
                        :headers="['Content']"
                        :rows="faqs"
                        :rows-per-page="10"
                        :showView="false"
                        :showEdit="true"
                        :showDelete="true"
                        :showCopy="false"
                        :showSearch="false"
                        :showUse="true"
                        :table="'faq'"
                        @action-event="faqsAction"
                    />
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style>

.tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}
.tabs button {
    padding: 10px;
    border: none;
    cursor: pointer;
}
.tabs button.active {
    background-color: #04549C;
    color: #fff;
}
.tab-content {
    padding: 20px;
}
</style>
