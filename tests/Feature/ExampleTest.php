<?php

test('returns a successful response', function () {
    $response = $this->get(route('post.index'));

    $response->assertOk();
});
