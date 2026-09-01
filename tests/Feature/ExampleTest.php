<?php

test('the application redirects home to login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});

test('login page returns a successful response', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});
