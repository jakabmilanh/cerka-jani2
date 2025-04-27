<template>
    <div id="hatter" style="height: 100%;">
    <section>
        <div class="bg-custom d-flex flex-column justify-content-center align-items-center fade-in"
            style="height: 420px; gap: 5px;">
            <div style="height: 5px; width: 400px; background-color: white;"></div>

            <img src="/images/cerka.png" alt="Cerka" class="img-fluid" style="max-height: 350px;">

            <div style="height: 5px; width: 400px; background-color: white;"></div>
        </div>





        <div class="container text-center my-5">
            <h1 class="fw-bold">Üdvözlünk, {{ $page.props.auth.user.name }} a Cerka oldalán!</h1>
            <p class="lead mt-4">
                A Cerka egy modern, könnyen kezelhető iskolai rendszer, amely segít a diákoknak és tanároknak a mindennapi
                teendőkben.
                A diákok megtekinthetik az órarendjüket, jegyeiket és osztályadataikat, míg a tanárok egyszerűen rögzíthetik
                az értékeléseket és
                kezelhetik az osztályok információit – mindezt egy felhasználóbarát online felületen keresztül.
            </p>
        </div>

        <div class="container my-5 ">
            <h2 class="text-center fw-bold mb-4">Miért válaszd a Cerkát?</h2>
            <div class="row text-center g-4">

                <div class="col-md-4">
                    <div class="p-4 bg-light rounded shadow h-100">
                        <i class="bi bi-calendar-check fs-1 text-primary mb-3"></i>
                        <h5 class="fw-bold">Digitális órarend</h5>
                        <p>Átlátható heti órarend diákoknak és tanároknak egyaránt, bárhonnan elérhetően.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-4 bg-light rounded shadow h-100">
                        <i class="bi bi-pencil-square fs-1 text-primary mb-3"></i>
                        <h5 class="fw-bold">Jegyek egyszerű kezelése</h5>
                        <p>A tanárok gyorsan és könnyedén rögzíthetik a jegyeket, a diákok pedig azonnal láthatják őket.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-4 bg-light rounded shadow h-100">
                        <i class="bi bi-people fs-1 text-primary mb-3"></i>
                        <h5 class="fw-bold">Osztályinformációk egy helyen</h5>
                        <p>Diák- és osztálylista, tanárok és diákok elérhetőségei, gyors elérésű információk.</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="container my-5 text-center">
            <div class="row g-4">
                <div class="col-md-3">
                    <h3 class="fw-bold">200+</h3>
                    <p>Regisztrált diák</p>
                </div>
                <div class="col-md-3">
                    <h3 class="fw-bold">30+</h3>
                    <p>Tanárok</p>
                </div>
                <div class="col-md-3">
                    <h3 class="fw-bold">5000+</h3>
                    <p>Rögzített jegy</p>
                </div>
                <div class="col-md-3">
                    <h3 class="fw-bold">100%</h3>
                    <p>Elégedettség</p>
                </div>
            </div>
        </div>

        <section id="contact" class="contact py-5 bg-light">
            <div class="container">
                <div class="row justify-content-center" data-aos="fade-up">
                    <div class="col-lg-10">
                        <div class="row text-center g-4">
                            <h2 class="fw-bold mb-4 text-center">Elérhetőségeink</h2>
                            <p class="lead mt-4">Esetleg, ha probléma lépne fel, vagy kérdésed lenne, az alábbiak közül
                                elérhetsz minket.</p>
                            <div class="col-md-4">
                                <div class="p-4 bg-white shadow rounded h-100">
                                    <i class="bi bi-geo-alt fs-1 text-primary mb-3"></i>
                                    <h4 class="mb-2">Helyileg:</h4>
                                    <p class="mb-0">II. Rákóczi Ferenc u. 48<br>Eger, 3300</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="p-4 bg-white shadow rounded h-100">
                                    <i class="bi bi-envelope fs-1 text-primary mb-3"></i>
                                    <h4 class="mb-2">Email:</h4>
                                    <p class="mb-0">Cerka@gmail.com</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="p-4 bg-white shadow rounded h-100">
                                    <i class="bi bi-phone fs-1 text-primary mb-3"></i>
                                    <h4 class="mb-2">Telefon:</h4>
                                    <p class="mb-0">+36 20 555 5555</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <svg viewBox="0 0 1440 320">
        <path fill="#1E3A8A" fill-opacity="1" d="M0,160L1440,32L1440,320L0,320Z"></path>
    </svg>
</div>
</template>
<style scoped>
  #hatter{
  background: linear-gradient(to bottom, #ffffff, #3d79e7);
}
.bg-custom {
    background-color: #1E3A8A;
}

.fade-in {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 1.2s ease-out forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}</style>
  