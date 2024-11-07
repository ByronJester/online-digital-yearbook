<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const editProfile = ref(false)

const props = defineProps({
    shares : {
        type: Array
    },
    user: {
        type: Object
    }
});

console.log(props.shares);

const getDP = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const defaultProfilePicture = getDP('images/default-profile.jpeg')
const profilePicturePreview = ref(props.user.profile_picture || defaultProfilePicture);

const carouselRef = ref(null);
let intervalId = null;

const startAutoplay = () => {
  if (carouselRef.value) {
    intervalId = setInterval(() => {
      if (carouselRef.value.next) {
        carouselRef.value.next();
      }
    }, 3000); // 3000ms = 3 seconds
  }
};

const stopAutoplay = () => {
  if (intervalId) {
    clearInterval(intervalId);
  }
};

onMounted(() => {
  startAutoplay();
});

onUnmounted(() => {
  stopAutoplay();
});

const toggleLike = async (post, status) => {
    if(post.type == 'album') {
        const formData = new FormData();
        formData.append('post_id', post.id);
        formData.append('status', status);

        await Inertia.post(route('staff-album-save-like'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
    } else {
        const formData = new FormData();
        formData.append('post_id', post.id);
        formData.append('status', status);

        await Inertia.post(route('staff-aap-save-like'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onSuccess: (page) => {
                // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
            },
            onError: (errors) => {
                // alert('There was an error uploading the file.');
            },
        });
    }

};

// Function to toggle the comment input for a specific post
const toggleCommentInput = (post) => {
    post.showCommentInput = !post.showCommentInput;
};

// Function to add a comment
const addComment = async (post, commentText) => {
    if(post.type == 'album') {
        const formData = new FormData();
        formData.append('post_id', post.id);
        formData.append('comment', commentText);

        await Inertia.post(route('staff-album-save-comment'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
    } else {
        const formData = new FormData();
        formData.append('post_id', post.id);
        formData.append('comment', commentText);

        await Inertia.post(route('staff-aap-save-comment'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onSuccess: (page) => {
                // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
            },
            onError: (errors) => {
                // alert('There was an error uploading the file.');
            },
        });
    }
};

const mediaSrc = ref(null);
const mediaType = ref(null);

const openMedia = (src, type) => {
    mediaSrc.value = src
    mediaType.value = type

    var modal = document.getElementById("defaultModal");
    modal.style.display = "block";
}

const closeModal = () => {
    mediaSrc.value = null
    mediaType.value = null

    var modal = document.getElementById("defaultModal");
    modal.style.display = "none";
}
</script>

<script>
     // If you are using PurgeCSS, make sure to whitelist the carousel CSS classes
    import 'vue3-carousel/dist/carousel.css'
    import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

    export default {
        name: 'App',
        components: {
            Carousel,
            Slide,
            Pagination,
            Navigation,
        },
    }
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="w-full my-3 border-b border-black flex flex-row">
            <div class="w-[30%] flex justify-center items-center">
                <img :src="profilePicturePreview" class="rounded-[50%] border border-black w-[200px] h-[200px] my-2">

            </div>

            <div class="w-[70%] flex justify-center items-center flex-col">
                <div class="w-full">
                    <p class="w-full text-xl">
                        <b>{{ user.fullname }}</b><span v-if="user.user_type == 'school_alumni'"> - {{ user.program + ' ' + user.section }} </span> <br> ({{ user.role }})
                    </p>
                </div>

                <div class="w-full">
                    <p class="text-xs mt-5 text-justify">{{ user.info }}</p>
                </div>
            </div>
        </div>

        <div
            id="defaultModal"
            tabindex="-1"
            aria-hidden="true"
            style="background-color: rgba(0, 0, 0, 0.7)"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full"
        >
            <div class="h-screen flex justify-center items-center">
                <div class="relative w-[50%] h-[500px] max-w-2xl max-h-full">



                    <div class="relative bg-white rounded-lg shadow" v-if="mediaType == 'image'">
                        <span class="text-xl float-right mr-2" @click="closeModal()">
                            <i class="fa-solid fa-xmark cursor-pointer"></i>
                        </span>
                        <img :src="mediaSrc" class="w-full h-[500px]"/>
                    </div>

                    <div class="relative bg-white rounded-lg shadow" v-else>

                        <video controls class="w-full h-full cursor-pointer">
                            <source :src="mediaSrc" type="video/mp4" />
                        </video>

                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="w-full flex flex-col">
                    <div class="w-full border rounded-lg p-4 bg-white h-full mt-5" v-for="post in shares" :key="post.id">
                        <div class="space-y-6" v-if="post.type == 'achievement'">
                            <div class="w-full">
                                <div class="font-bold">{{ post.user.fullname }}</div>
                                <p>{{ post.content }}</p>

                                <!-- Display image or video if available -->

                                <div v-if="post.image" class="my-4 flex justify-center items-center">
                                    <img :src="post.image" alt="Post Image" class="w-[500px] h-[300px]" @click="openMedia(post.image, 'image')">
                                </div>
                                <div v-if="post.video" class="my-4">
                                    <video controls class="w-[500px] h-[300px]">
                                        <source :src="post.video" type="video/mp4" />
                                    </video>
                                </div>

                                <!-- Likes and comments -->
                                <div class="w-full mt-4">
                                    <button @click="toggleLike(post, post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0
                                        ? 'Unlike'
                                        : 'Like' )"
                                        class="text-blue-500"
                                    >
                                        {{ post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0
                                            ? 'Unlike'
                                            : 'Like'
                                        }}
                                        <i class="fa fa-thumbs-up"></i> {{ post.likes.length }}
                                        <!-- {{ post.likes }} -->
                                    </button>
                                    <button @click="toggleCommentInput(post)" class="text-blue-500 float-right">
                                        <i class="fa fa-comment"></i> {{ post.comments.length }}
                                    </button>
                                </div>

                                <!-- Comment input, displayed above other comments -->
                                <div v-if="post.showCommentInput" class="mt-4">
                                    <input type="text" placeholder="Add a comment..."
                                        @keyup.enter="addComment(post, $event.target.value); $event.target.value = ''"
                                        class="border p-1 rounded w-full mb-2">
                                </div>

                                <!-- Display comments -->
                                <div v-if="post.comments.length > 0 && post.showCommentInput  " class="mt-4 ml-4">
                                    <div v-for="comment in post.comments" :key="comment.id" class="border-t pt-2 mt-2">
                                        <p class="font-bold">{{ comment.commentor }}</p>
                                        <p class="ml-3">{{ comment.comment }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full" v-else>
                            <div class="w-full text-center border border-black rounded-md mt-3">
                                <p class="text-2xl p-3">{{ post.content }}</p>
                            </div>

                            <div class="w-full text-center border border-black rounded-md mt-3" v-if="post.description">
                                <p class="text-md p-3">{{ post.description }}</p>
                            </div>

                            <!-- Display multiple images if available -->
                            <div v-if="post.image.length > 0" class="my-4">
                                <div class="w-full carousel-container">
                                    <carousel :items-to-show="2" ref="carouselRef">
                                        <slide v-for="i in post.image" :key="i">
                                            <img :src="i" class="w-full h-[300px] mr-1" @click="openMedia(i, 'image')"/>
                                        </slide>

                                        <template #addons>
                                            <!-- <navigation /> -->
                                            <pagination />
                                        </template>
                                    </carousel>
                                 </div>
                            </div>

                            <!-- Display multiple videos if available -->
                            <div v-if="post.video.length > 0" class="my-4">
                                <div class="w-full carousel-container">
                                    <carousel :items-to-show="2" ref="carouselRef">
                                        <slide v-for="i in post.video" :key="i">
                                            <!-- <img :src="i" class="w-full h-[200px] mr-1"/> -->
                                            <video controls class="w-full h-[300px]">
                                                <source :src="i" type="video" />
                                            </video>
                                        </slide>

                                        <template #addons>
                                            <!-- <navigation /> -->
                                            <pagination />
                                        </template>
                                    </carousel>
                                 </div>
                            </div>

                            <!-- Likes and comments -->
                            <div class="w-full mt-20">
                                <button @click="toggleLike(post, post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0 ? 'Unlike' : 'Like' )" class="text-blue-500">
                                    {{ post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0 ? 'Unlike' : 'Like' }}
                                    <i class="fa fa-thumbs-up"></i> {{ post.likes.length }}
                                </button>


                                <button @click="toggleCommentInput(post)" class="text-blue-500 float-right">
                                    <i class="fa fa-comment"></i> {{ post.comments.length }}
                                </button>
                            </div>

                            <!-- Comment input, displayed above other comments -->
                            <div v-if="post.showCommentInput" class="mt-4">
                                <input type="text" placeholder="Add a comment..." @keyup.enter="addComment(post, $event.target.value); $event.target.value = ''" class="border p-1 rounded w-full mb-2">
                            </div>

                            <!-- Display comments -->
                            <div v-if="post.comments.length > 0 && post.showCommentInput" class="mt-4 ml-4">
                                <div v-for="comment in post.comments" :key="comment.id" class="border-t pt-2 mt-2">
                                    <p class="font-bold">{{ comment.commentor }}</p>
                                    <p class="ml-3">{{ comment.comment }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
