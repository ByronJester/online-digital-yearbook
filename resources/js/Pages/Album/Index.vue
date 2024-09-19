<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';

defineProps({
    posts: {
        type: Array,
    },
});

// Form data for new post
const postContent = ref('');
const postImages = ref([]); // Array to hold multiple images
const postVideos = ref([]); // Array to hold multiple videos

// File input refs to trigger from buttons
const imageInput = ref(null);
const videoInput = ref(null);

const handleImageUpload = (e) => {
    postImages.value = Array.from(e.target.files); // Store all selected images
};

const handleVideoUpload = (e) => {
    postVideos.value = Array.from(e.target.files); // Store all selected videos
};

// Function to create a new post
const createPost = async () => {
    const formData = new FormData();
    formData.append('content', postContent.value);

    // Append all images to FormData
    postImages.value.forEach((image, index) => {
        console.log(image)
        formData.append(`images[${index}]`, image);
    });

    // Append all videos to FormData
    postVideos.value.forEach((video, index) => {
        formData.append(`videos[${index}]`, video);
    });

    await Inertia.post(route('staff-album-save-post'), formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        onSuccess: (page) => {
            // Handle success
        },
        onError: (errors) => {
            // Handle error
        },
    });

    // Reset form
    postContent.value = '';
    postImages.value = [];
    postVideos.value = [];
};

// Function to handle liking a post
const toggleLike = async (post, status) => {
    const formData = new FormData();
    formData.append('post_id', post.id);
    formData.append('status', status);

    await Inertia.post(route('staff-album-save-like'), formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
};

// Function to toggle the comment input for a specific post
const toggleCommentInput = (post) => {
    post.showCommentInput = !post.showCommentInput;
};

// Function to add a comment
const addComment = async (post, commentText) => {
    const formData = new FormData();
    formData.append('post_id', post.id);
    formData.append('comment', commentText);

    await Inertia.post(route('staff-album-save-comment'), formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
};

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
    <Head title="School Album" />

    <AuthenticatedLayout>
        <!-- Create new post form -->
        <div class="mb-10 border-2 border-black rounded-md p-5">
            <h2 class="text-xl font-bold mb-2">New Albums</h2>
            <textarea v-model="postContent" placeholder="What's on your mind?" class="w-full p-2 border mb-4 rounded-lg" rows="5"></textarea>

            <!-- Upload Buttons with Hidden Inputs for Multiple Files -->
            <div class="flex gap-4 mb-4">
                <button @click="imageInput.click()" class="bg-blue-500 text-white px-4 py-2 rounded">Upload Photos</button>
                <button @click="videoInput.click()" class="bg-blue-500 text-white px-4 py-2 rounded">Upload Videos</button>

                <!-- Hidden File Inputs -->
                <input ref="imageInput" type="file" multiple @change="handleImageUpload" accept="image/*" class="hidden" />
                <input ref="videoInput" type="file" multiple @change="handleVideoUpload" accept="video/*" class="hidden" />
            </div>

            <button @click="createPost" class="bg-blue-500 text-white px-4 py-2 float-right rounded-md">Post</button>
        </div>

        <!-- News feed posts -->
        <div class="space-y-6">
            <div v-for="post in posts" :key="post.id" class="border rounded-lg p-4 bg-white h-[100%]">
                <div class="font-bold">{{ post.user.fullname }}</div>
                <p>{{ post.content }}</p>

                <!-- Display multiple images if available -->
                <div v-if="post.image.length > 0" class="my-4">
                    <div class="w-full carousel-container">
                        <carousel :items-to-show="2" ref="carouselRef">
                            <slide v-for="i in post.image" :key="i">
                                <img :src="i" class="w-full h-[200px] mr-1"/>
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
                                <video controls class="w-full h-[200px]">
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
                <div class="flex justify-between items-center mt-20">
                    <button @click="toggleLike(post, post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0 ? 'Unlike' : 'Like' )" class="text-blue-500">
                        {{ post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0 ? 'Unlike' : 'Like' }}
                        <i class="fa fa-thumbs-up"></i> {{ post.likes.length }}
                    </button>
                    <button @click="toggleCommentInput(post)" class="text-blue-500">
                        <i class="fa fa-comment"></i> {{ post.comments.length }}
                    </button>
                </div>

                <!-- Comment input, displayed above other comments -->
                <div v-if="post.showCommentInput" class="mt-4">
                    <input type="text" placeholder="Add a comment..." @keyup.enter="addComment(post, $event.target.value); $event.target.value = ''" class="border p-1 rounded w-full mb-2">
                </div>

                <!-- Display comments -->
                <div v-if="post.comments.length > 0" class="mt-4 ml-4">
                    <div v-for="comment in post.comments" :key="comment.id" class="border-t pt-2 mt-2">
                        <p class="font-bold">{{ comment.commentor }}</p>
                        <p class="ml-3">{{ comment.comment }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
textarea {
    resize: none;
}

.carousel-container {
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
  }
</style>
