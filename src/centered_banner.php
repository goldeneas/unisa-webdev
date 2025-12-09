<?php
function spawn_centered_banner($title, $subtitle) {
    printf('
        <body>
            <main id="id">
                <section id="id-link" class="form-centered">
                    <h3 class="title">%s</h3>
                    <p class="redirect">
                        %s
                    </p>
                </section>
            </main>
        </body>
    ', $title, $subtitle);
}
?>
