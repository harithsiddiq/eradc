# Interface Contracts: Routes

## Web Routes

- `GET /profile` -> `ProfileController@show`
- `PUT /profile/password` -> `ProfileController@updatePassword`
- `GET /learn/{course}/{lesson}` -> `LessonController@show` (P3)
- `POST /learn/{course}/{lesson}/progress` -> `LessonController@updateProgress` (P3)
