<?php
function spawn_centered_banner($title, $subtitle) {
    printf('
        <body>
            <div id="id">
                <div id="id-link" class="form-centered">
                    <h3 class="title">%s</h3>
                    <p class="redirect">
                        %s
                    </p>
                </div>
            </div>
        </body>
    ', $title, $subtitle);
}
?>
