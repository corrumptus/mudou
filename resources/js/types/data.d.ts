export type ClassElementType = "file" |
    "link" |
    "text" |
    "poll" |
    "homework" |
    "group" |
    "quiz";

export type Class = {
    name: string,
    elements: {
        name: string,
        type: ClassElementType
    }[]
};