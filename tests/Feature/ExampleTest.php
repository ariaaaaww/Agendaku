<?php

test('the application redirects home to login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});

test('login page returns a successful response', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('register page returns a successful response', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('student dashboard view still renders without authentication', function () {
    $response = $this->get('/students');

    $response->assertStatus(200);
});
