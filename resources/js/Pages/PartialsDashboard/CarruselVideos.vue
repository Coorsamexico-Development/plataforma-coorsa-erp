<script >
import { Inertia } from '@inertiajs/inertia'
import { onMounted, reactive, ref, watch } from 'vue'

let imgs = ref();


</script>
<template>
    <div class="carousel">
        <div class="image-container" id="imgs" ref="imgs">
            <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1500&q=80" alt="first-image">
            <img src="https://images.unsplash.com/photo-1523712999610-f77fbcfc3843?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1500&q=80" alt="second-image">
            <img src="https://images.unsplash.com/photo-1664295776783-6e72b6ab1387?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1500&q=80" alt="third-image">
            <img src="https://images.unsplash.com/photo-1663875919989-62c9fa1d2da7?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1500&q=80" alt="fourth-image">
        </div>
        <div class="buttons-container">
            <button @click="leftbutton" id="left" class="btn">Previous</button>
            <button @click="rightbutton" id="right" class="btn">Next</button>
        </div>
    </div>
</template>