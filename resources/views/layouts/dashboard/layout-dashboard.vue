<script setup lang="ts">
import { Modal } from "momentum-modal"
import Navbar from "./partials/navbar.vue"
</script>

<template>
    <div class="min-h-full">
        <div class="w-full bg-blue-600">
            <Navbar />
        </div>
        <main>
            <slot />
        </main>

        <Modal />
    </div>
</template>
