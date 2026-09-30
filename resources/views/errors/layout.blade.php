@extends('errors.minimal')

@section('title', $exception->getMessage() ?: __('Error'))
@section('code', $exception->getStatusCode() ?: '500')
@section('message', $exception->getMessage() ?: __('System Error'))
@section('message2', __('An unexpected error has occurred on the server.'))
