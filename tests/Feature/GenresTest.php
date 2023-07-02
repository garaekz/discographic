<?php

it('has genres page', function () {
    $response = $this->get('/genres');

    $response->assertStatus(200);
});
