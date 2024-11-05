<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    data: {
        type: Array,
    },
    notifications: {
        type: Array
    }
});

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

const textComment = ref(null);
const commentId = ref(null);
// Function to add a comment
const addComment = async (post, commentText) => {
    if(post.type == 'album') {
        if(!commentId.value) {
            const formData = new FormData();
            formData.append('post_id', post.id);
            formData.append('comment', commentText);

            await Inertia.post(route('staff-album-save-comment'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            textComment.value = null
            commentId.value = null
        } else {
            const formData = new FormData();
            formData.append('id', commentId.value);
            formData.append('comment', textComment.value);

            await Inertia.post(route('staff-album-edit-comment'), formData, {

                onSuccess: (page) => {
                    // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
                    textComment.value = null
                    commentId.value = null
                },
                onError: (errors) => {
                    // alert('There was an error uploading the file.');
                },
            });
        }

    } else {
        if(!commentId.value) {
            const formData = new FormData();
            formData.append('post_id', post.id);
            formData.append('comment', commentText);

            await Inertia.post(route('staff-aap-save-comment'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                onSuccess: (page) => {
                    // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
                    textComment.value = null
                    commentId.value = null
                },
                onError: (errors) => {
                    // alert('There was an error uploading the file.');
                },
            });
        } else {
            const formData = new FormData();
            formData.append('id', commentId.value);
            formData.append('comment', textComment.value);

            await Inertia.post(route('staff-aap-edit-comment'), formData, {

                onSuccess: (page) => {
                    // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
                    textComment.value = null
                    commentId.value = null
                },
                onError: (errors) => {
                    // alert('There was an error uploading the file.');
                },
            });
        }

    }
};

const sharePost = (post) => {
    swal({
        title: "Are you sure to share this feed ?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            const formData = new FormData();
            formData.append('post_id', post.id);
            formData.append('post_type', post.type);

            Inertia.post(route('alumni-share-feed'), formData, {
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

const deleteComment = (id, type) => {
    swal({
        title: "Are you sure to delete this comment?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            if(type == 'album') {
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
            } else {
                const formData = new FormData();
                formData.append('id', id);

                Inertia.post(route('staff-aap-delete-comment'), formData, {
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

        }
    });
}

const postData = ref([])
postData.value = props.data

const search = (event) => {
    let search = event.target.value
    console.log(search)
    console.log(props.data)

    if(search == '') {
        postData.value = props.data
    } else {
        postData.value = props.data.filter(x => { return x.content.toLowerCase().includes(search.toLowerCase()) || x.user.fullname.toLowerCase().includes(search.toLowerCase())});
    }
}

const viewProfile = (id) => {
    Inertia.get(route('profile.view', id))
}

const notificationModalVisible = ref(false);

const openNotificationModal = () => {
    notificationModalVisible.value = true;
};

const closeNotificationModal = () => {
    notificationModalVisible.value = false;
};

const openNotification = (n) => {
    if(n.type =='album') {
        Inertia.get(route('album.view', n.redirect_id))
    } else {
        Inertia.get(route('achievement.view', n.redirect_id))
    }
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
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="w-full p-5">
            <div class="w-full flex flex-col">
                <div class="w-full mb-10">
                    <span class="float-right text-2xl text-blue-500 cursor-pointer" @click="openNotificationModal">
                        <i class="fa-solid fa-earth-americas"></i>
                    </span>
                </div>

                <div v-if="notificationModalVisible" class="notification-modal bg-white shadow-lg rounded-lg">
                    <div class="bg-white rounded-lg w-full">
                        <div class="w-full mb-5 p-5">
                            <span class="float-right cursor-pointer" @click="closeNotificationModal">
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full p-3 text-center text-xl mb-5" v-if="notifications.length == 0">
                            You have no notification(s).
                        </div>

                        <div class="w-full flex flex-col p-2" v-else>
                            <div class="w-full border-b border-black mb-3 cursor-pointer" v-for="n in notifications" :key="n.id"
                                @click="openNotification(n)"
                            >
                                {{ n.message }}
                            </div>
                        </div>


                    </div>
                </div>

                <div class="w-full flex justify-center items-center">
                    <input type="text" placeholder="Search..." class="rounded-2xl w-[50%] mb-10" @keyup="search($event)"/>
                </div>

                <div class="w-full border rounded-lg p-4 bg-white h-full mt-5" v-for="post in postData" :key="post.id">
                    <div class="space-y-6" v-if="post.type == 'achievement'">
                        <div class="w-full">
                            <div class="font-bold cursor-pointer" @click="viewProfile(post.user.id)">
                                {{ post.user.fullname }}
                            </div>
                            <p class="text-xs mt-2 mb-5">{{ post.created_at }}</p>

                            <p>{{ post.content }}</p>

                            <!-- Display image or video if available -->

                            <div v-if="post.image" class="my-4 flex justify-center items-center">
                                <img :src="post.image" alt="Post Image" class="w-[500px] h-[300px]">
                            </div>
                            <div v-if="post.video" class="my-4 flex justify-center items-center">
                                <video controls class="w-[500px] h-[300px]">
                                    <source :src="post.video" type="video/mp4" />
                                </video>
                            </div>

                            <!-- Likes and comments -->
                            <div class="w-full mt-4">
                                <button @click="toggleLike(post, post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0
                                    ? 'Unlike'
                                    : 'Like' )"
                                    :class="{'text-blue-500': post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0}"
                                >
                                    <!-- {{ post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0
                                        ? 'Unlike'
                                        : 'Like'
                                    }} -->
                                    <i class="fa fa-thumbs-up"></i> {{ post.likes.length }}
                                    <!-- {{ post.likes }} -->
                                </button>
                                <button class="text-green-500 float-right ml-2" @click="sharePost(post)" v-if="$page.props.auth.user.user_type == 'school_alumni'">
                                    <i class="fa fa-share"></i> {{ post.share_user_names.length }}
                                </button>
                                <button @click="toggleCommentInput(post)" class="text-blue-500 float-right">
                                    <i class="fa fa-comment"></i> {{ post.comments.length }}
                                </button>
                            </div>

                            <!-- Comment input, displayed above other comments -->
                            <div v-if="post.showCommentInput" class="mt-4">
                                <input type="text" placeholder="Add a comment..."
                                    @keyup.enter="addComment(post, $event.target.value); $event.target.value = ''"
                                    v-model="textComment"
                                    class="border p-1 rounded w-full mb-2">
                            </div>

                            <!-- Display comments -->
                            <div v-if="post.comments.length > 0 && post.showCommentInput  " class="mt-4 ml-4">
                                <div v-for="comment in post.comments" :key="comment.id" class="border-t pt-2 mt-2">
                                    <p class="font-bold">{{ comment.commentor }}</p>
                                    <p class="ml-3">
                                        <span>{{ comment.comment }}</span>

                                        <span class="float-right text-red-500 text-xs" v-if="$page.props.auth.user.id == comment.user_id ">
                                            <i class="fa-solid fa-trash ml-2 cursor-pointer" @click="deleteComment(comment.id, post.type)">
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

                    <div class="w-full" v-else>
                        <div class="font-bold cursor-pointer" @click="viewProfile(post.user.id)">
                            {{ post.user.fullname }}
                        </div>
                        <p class="text-xs mt-1 mb-10">{{ post.created_at }}</p>

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
                                        <img :src="i" class="w-full h-[300px] mr-1"/>
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
                            <button @click="toggleLike(post, post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0 ? 'Unlike' : 'Like' )" :class="{'text-blue-500': post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0}">
                                <!-- {{ post.likes.filter( x => { return x.user_id == $page.props.auth.user.id }).length > 0 ? 'Unlike' : 'Like' }} -->
                                <i class="fa fa-thumbs-up"></i> {{ post.likes.length }}
                            </button>

                            <button class="text-green-500 float-right ml-2" @click="sharePost(post)" v-if="$page.props.auth.user.user_type == 'school_alumni'">
                                <i class="fa fa-share"></i>
                            </button>
                            <button @click="toggleCommentInput(post)" class="text-blue-500 float-right">
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
                                        <i class="fa-solid fa-trash ml-2 cursor-pointer" @click="deleteComment(comment.id, post.type)">
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
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.student {
    border-radius: 10px;
    border: 1px solid black;
}

.scroll-container {
    overflow: hidden; /* Hides the scrollbar */
    overflow-y: auto; /* Enables vertical scrolling */
    scrollbar-width: none; /* For Firefox */
    -ms-overflow-style: none; /* For IE and Edge */
}

/* Hides the scrollbar in WebKit-based browsers (Chrome, Safari) */
.scroll-container::-webkit-scrollbar {
    display: none;
}

.notification-modal {
    position: absolute;
    right: 60px;
    top: 150px;
    width: 350px;
}
</style>
