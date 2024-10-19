<template>
    <div>
        <div class="not-found">
            <div class="section-container">
                <p>404 | NOT FOUND</p>
                <router-link to="/inventory/dashboard"
                    >Go Dashboard</router-link
                >
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
p {
    color: #0ecc8a;
    font-weight: 900;
    font-size: 20px;
    font-family: Helvetica, Arial, sans-serif;
}

.not-found {
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.not-found a {
    text-decoration: underline;
}
</style>
