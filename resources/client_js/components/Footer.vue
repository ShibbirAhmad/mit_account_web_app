<template>
    <v-footer padless app inset>
        <v-card class="flex" flat tile color="#fff" :elevation="10">
            
            <div class="footer__section">
                <p>Version : 1.0</p>
                <p>
                    {{ new Date().getFullYear() }} — <strong>Mohasagor IT Solutions</strong>
                </p>
            </div>
        </v-card>
    </v-footer>
</template>
