<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { VueSpinner } from 'vue3-spinners';

defineProps({
    posts: {
        type: Array,
    },
});

// Form data for new post
const postContent = ref('');
const postDescription = ref('');
const postImages = ref([]);
const postVideos = ref([]);
const postImagePreviews = ref([]);
const postVideoPreviews = ref([]);
const archiveDate = ref(new Date(new Date().setFullYear(new Date().getFullYear() + 1)).toISOString().split('T')[0])

const handleImageUpload = (e) => {
    const files = Array.from(e.target.files);
    postImages.value = files; // Store selected images
    postImagePreviews.value = []; // Reset previews

    files.forEach((file) => {
        const reader = new FileReader();
        reader.onload = () => {
            postImagePreviews.value.push(reader.result); // Store preview URL
        };
        reader.readAsDataURL(file);
    });
};

const handleVideoUpload = (e) => {
    const files = Array.from(e.target.files);
    postVideos.value = files; // Store selected videos
    postVideoPreviews.value = []; // Reset previews

    files.forEach((file) => {
        const reader = new FileReader();
        reader.onload = () => {
            postVideoPreviews.value.push(reader.result); // Store preview URL
        };
        reader.readAsDataURL(file);
    });
};

// File input refs to trigger from buttons
const imageInput = ref(null);
const videoInput = ref(null);

// const handleImageUpload = (e) => {
//     postImages.value = Array.from(e.target.files); // Store all selected images
// };

// const handleVideoUpload = (e) => {
//     postVideos.value = Array.from(e.target.files); // Store all selected videos
// };

const loading = ref(false)

// Function to create a new post
const createPost = () => {
    swal({
        title: "Are you sure to save this album?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            const formData = new FormData();
            formData.append('content', postContent.value);
            formData.append('description', postDescription.value)
            formData.append('archive_at', archiveDate.value);

            // Append all images to FormData
            postImages.value.forEach((image, index) => {
                console.log(image)
                formData.append(`images[${index}]`, image);
            });

            // Append all videos to FormData
            postVideos.value.forEach((video, index) => {
                formData.append(`videos[${index}]`, video);
            });



            setTimeout(() => {
                Inertia.post(route('staff-album-save-post'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                    onSuccess: (page) => {
                        loading.value = false
                        // Handle success
                    },
                    onError: (errors) => {
                        // Handle error
                        loading.value = false
                    },
                });

            }, 1000);

            // Reset form
            postContent.value = '';
            postImages.value = [];
            postVideos.value = [];

        }
    });


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
const textComment = ref(null);
const commentId = ref(null);

const formComment = useForm({
    post_id: '',
    comment: '',
});

const addComment = async (post, commentText) => {
    if(!commentId.value) {
        formComment.post_id = post.id
        formComment.comment = commentText
        // const formData = new FormData();
        // formData.append('post_id', post.id);
        // formData.append('comment', commentText);
        // formData.append('user_id', post.user_id);

        // await Inertia.post(route('staff-album-save-comment'), formData, {
        //     headers: {
        //         'Content-Type': 'multipart/form-data',
        //     },
        // });
        // textComment.value = null
        // commentId.value = null

        await formComment.post(route('staff-album-save-comment'), {
            onSuccess: (page) => {
                location.reload()
            },
            onError: (errors) => {
                formComment.post_id = null
                formComment.comment = null
                alert('You commented a bad word');
            }
        });
    } else {
        const formData = new FormData();
        formData.append('id', commentId.value);
        formData.append('comment', textComment.value);

        await Inertia.post(route('staff-album-edit-comment'), formData, {

            onSuccess: (page) => {
                // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');

            },
            onError: (errors) => {
                // alert('There was an error uploading the file.');
            },
        });
    }

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

const deletePost = (id) => {
    swal({
        title: "Are you sure to delete this album?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            const formData = new FormData();
            formData.append('id', id);

            Inertia.post(route('delete-album'), formData, {
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
    });
}

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

const deleteComment = (id) => {
    swal({
        title: "Are you sure to delete this comment?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            const formData = new FormData();
            formData.append('id', id);

            Inertia.post(route('staff-album-delete-comment'), formData, {
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
    });
}

const viewProfile = (id) => {
    Inertia.get(route('profile.view', id))
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
    <Head title="School Album" />

    <AuthenticatedLayout>
        <!-- Create new post form -->
        <div class="mb-5  p-5" :class="{'border-2 border-black rounded-md':  !loading && $page.props.auth.user.user_type == 'shool_staff'}" >
            <div class="w-full flex justify-center items-center" v-if="loading">
                <VueSpinner size="50" color="red" />
            </div>

            <div class="w-full border-2 border-black p-5 rounded-md" v-if="$page.props.auth.user.user_type == 'school_staff'">
                <h2 class="text-xl font-bold mb-2">New Albums</h2>
                <textarea v-model="postContent" placeholder="Album Name" class="w-full p-2 border mb-4 rounded-lg" rows="1"></textarea>

                <textarea v-model="postDescription" placeholder="Album Description" class="w-full p-2 border mb-4 rounded-lg" rows="5"></textarea>

                <!-- <div class="w-full">
                    <label>Archive Date:</label><br>
                    <input type="date" class="rounded-md mb-5" v-model="archiveDate"/>
                </div> -->

                <div v-if="postImagePreviews.length" class="flex gap-2 mt-2">
                    <div v-for="(preview, index) in postImagePreviews" :key="index" class="w-32 h-32">
                        <img :src="preview" alt="Image preview" class="w-full h-full object-cover rounded-md" />
                    </div>
                </div>

                <!-- Video Previews -->
                <div v-if="postVideoPreviews.length" class="flex gap-2 mt-5">
                    <div v-for="(preview, index) in postVideoPreviews" :key="index" class="w-32 h-32">
                        <video :src="preview" controls class="w-full h-full object-cover rounded-md"></video>
                    </div>
                </div>

                <!-- Upload Buttons with Hidden Inputs for Multiple Files -->
                <div class="flex gap-4 mb-4 mt-4">
                    <div class="w-full">
                        <button @click="imageInput.click()" class="bg-blue-500 text-white px-4 py-2 rounded mr-1">Upload Photos</button>
                        <button @click="videoInput.click()" class="bg-blue-500 text-white px-4 py-2 rounded">Upload Videos</button>
                    </div>



                    <!-- Hidden File Inputs -->
                    <input ref="imageInput" type="file" multiple @change="handleImageUpload" accept="image/*" class="hidden" />
                    <input ref="videoInput" type="file" multiple @change="handleVideoUpload" accept="video/*" class="hidden" />
                    <button @click="createPost" class="bg-blue-500 text-white px-4 py-2 float-right rounded-md">Save</button>
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

        <!-- News feed posts -->
        <div class="space-y-6">
            <div v-for="post in posts" :key="post.id" class="border rounded-lg p-4 bg-white h-[100%]">
                <!-- <div class="font-bold">{{ post.user.fullname }}</div> -->
                <div class="font-bold">
                    <span class="cursor-pointer" @click="viewProfile(post.user.id)">
                        {{ post.user.fullname }}
                    </span>
                    <span class="float-right text-xs text-red-500 cursor-pointer" v-if="$page.props.auth.user.user_type == 'school_staff'"
                        @click="deletePost(post.id)"
                    >
                        Delete
                    </span>
                </div>

                <p class="text-xs">{{ post.created_at }}</p>

                <div class="w-full text-center border border-black rounded-md mt-8">
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
                                <img :src="i" class="w-full h-[300px] mr-1 cursor-pointer" @click="openMedia(i, 'image')"/>
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
                                <video controls class="w-full h-[250px] cursor-pointer">
                                    <source :src="i" type="video/mp4" />
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
                <div class="flex justify-between items-center mt-20" v-if="$page.props.auth.user.user_type == 'school_staff'">
                    <button @click="toggleLike(post, post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0 ? 'Unlike' : 'Like' )"
                            :class="{'text-blue-500': post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0}"
                        >
                        <!-- {{ post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0 ? 'Unlike' : 'Like' }} -->
                        <i class="fa fa-thumbs-up"></i> {{ post.likes.length }}
                    </button>

                    <button @click="toggleCommentInput(post)" class="text-blue-500">
                        <i class="fa fa-comment"></i> {{ post.comments.length }}
                    </button>
                </div>

                <!-- Comment input, displayed above other comments -->
                <div v-if="post.showCommentInput" class="mt-4">
                    <input type="text" placeholder="Add a comment..." @keyup.enter="addComment(post, $event.target.value); $event.target.value = ''" class="border p-1 rounded w-full mb-2"
                        v-model="textComment"
                    >
                </div>

                <!-- Display comments -->
                <div v-if="post.comments.length > 0 && post.showCommentInput" class="mt-4 ml-4">
                    <div v-for="comment in post.comments" :key="comment.id" class="border-t pt-2 mt-2">
                        <p class="font-bold">{{ comment.commentor }}</p>

                        <p class="ml-3">
                            <span>{{ comment.comment }}</span>

                            <span class="float-right text-red-500 text-xs" v-if="$page.props.auth.user.id == comment.user_id ">
                                <i class="fa-solid fa-trash ml-2 cursor-pointer" @click="deleteComment(comment.id)">
                                </i>
                            </span>
                            <span class="float-right text-green-500 text-xs" v-if="$page.props.auth.user.id == comment.user_id ">
                                <i class="fa-solid fa-pen-to-square cursor-pointer" @click="textComment = comment.comment; commentId = comment.id">

                                </i>
                            </span>
                        </p>

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
